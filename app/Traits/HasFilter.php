<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

trait HasFilter
{
    protected array $defaultFilters = [
        'limit'     => 15,
        'sort_key'  => 'created_at',
        'sort_type' => 'desc',
    ];

    protected function applyLimit(Builder &$query, $request): Builder
    {
        if ($request->has('limit') && is_numeric($request->limit) && isset($this->filterLimit)) {
            $this->perPage = min((int) $request->limit, $this->filterLimit);
        }
        return $query;
    }

    protected function applySort(Builder &$query, $request): Builder
    {
        if ($request->has('sort_key') && isset($this->filterSort) && in_array($request->sort_key, $this->filterSort)) {
            $direction = in_array($request->sort_type, ['asc', 'desc']) ? $request->sort_type : 'asc';
            $query->orderBy($request->sort_key, $direction);
        } else {
            if (! empty($this->filterSort)) {
                $query->orderBy($this->filterSort[0], $this->defaultFilters['sort_type']);
            }
        }
        return $query;
    }

    protected function applySearch(Builder &$query, $request): Builder
    {
        if ($request->has('search') && ! empty($this->filterSearchCols)) {
            $lang       = app()->getLocale();
            $searchTerm = strtolower(trim($request->search));

            $concatMap = property_exists($this, 'filterSearchConcat') && is_array($this->filterSearchConcat)
                ? $this->filterSearchConcat
                : [];

            $table = $this->getTable();

            $query->where(function ($q) use ($searchTerm, $lang, $concatMap, $table) {
                foreach ($this->filterSearchCols as $column) {
                    if (array_key_exists($column, $concatMap)) {
                        $cols = (array) $concatMap[$column];
                        if (empty($cols)) {
                            continue;
                        }
                        $parts = array_map(function ($c) use ($table) {
                            return "COALESCE(`{$table}`.`{$c}`, '')";
                        }, $cols);
                        $expr = "LOWER(CONCAT_WS(' ', " . implode(', ', $parts) . ")) LIKE ?";
                        $q->orWhereRaw($expr, ['%' . $searchTerm . '%']);
                        continue;
                    }

                    if (property_exists($this, 'translatable') && in_array($column, $this->translatable)) {
                        $q->orWhereRaw(
                            "LOWER(JSON_UNQUOTE(JSON_EXTRACT(`{$table}`.`{$column}`, '$." . $lang . "'))) LIKE ?",
                            ['%' . $searchTerm . '%']
                        );
                    } else {
                        $q->orWhereRaw(
                            "LOWER(`{$table}`.`{$column}`) LIKE ?",
                            ['%' . $searchTerm . '%']
                        );
                    }
                }
            });
        }
        return $query;
    }

    protected function applyColumnFilters(Builder &$query, $request): Builder
    {
        if (! empty($this->filterCols)) {
            foreach ($this->filterCols as $column) {

                if ($request->has($column)) {
                    $values = explode(',', $request->$column);
                    $query->whereIn($this->getTable() . '.' . $column, $values);
                }
            }
        }
        return $query;
    }

    protected function applyRelationFilters(Builder &$query, $request): Builder
    {
        if (! empty($this->filterColsChilds)) {
            foreach ($this->filterColsChilds as $alias => $relation) {

                if ($request->has("{$relation}_{$alias}")) {
                    $query->whereHas($relation, function ($q) use ($alias, $request, $relation) {
                        $values       = explode(',', $request["{$relation}_{$alias}"]);
                        $actualColumn = preg_replace('/\d+$/', '', $alias);
                        $q->whereIn($actualColumn, $values);
                    });
                }
            }
        }
        return $query;
    }

    protected function applyDateFilters(Builder &$query, $request): Builder
    {
        $dates = $this->filterDates ?? $this->filterOnlyDates ?? [];
        foreach ($dates as $input => $column) {
            if ($request->has($input)) {
                $date = $request->$input;
                if (is_array($date)) {
                    $query->whereBetween(DB::raw("DATE({$this->getTable()}.{$column})"), [$date['start'], $date['end']]);
                } else {
                    $query->whereDate($this->getTable() . '.' . $column, $date);
                }
            }
        }
        return $query;
    }

    protected function applyRangeFilters(Builder &$query, $request): Builder
    {
        if (! empty($this->filterBetween)) {
            foreach ($this->filterBetween as $column => $inputs) {
                if ($request->has($inputs['from']) || $request->has($inputs['to'])) {
                    if ($request->has($inputs['from'])) {
                        $query->where($column, '>=', $request->input($inputs['from']));
                    }
                    if ($request->has($inputs['to'])) {
                        $query->where($column, '<=', $request->input($inputs['to']));
                    }
                }
            }
        }
        return $query;
    }

    protected function applyTimeFilters(Builder &$query, $request): Builder
    {
        if (! empty($this->filterTime)) {
            foreach ($this->filterTime as $input => $column) {
                if ($request->has($input)) {
                    Validator::make([$input => $request->$input], [
                        $input => ['required', 'regex:/^(\d{2}:\d{2}:\d{2}-\d{2}:\d{2}:\d{2})$/i'],
                    ])->validate();

                    [$start, $end] = explode('-', $request->$input);
                    $query->whereRaw(
                        "(TIME({$this->getTable()}.{$column}) >= ? OR TIME({$this->getTable()}.{$column}) <= ?)",
                        [$start, $end]
                    );
                }
            }
        }
        return $query;
    }

    protected function applyBooleanFilters(Builder &$query, $request): Builder
    {
        if (! empty($this->filterBoolean)) {
            foreach ($this->filterBoolean as $column) {
                if ($request->has($column)) {
                    $query->where($this->getTable() . '.' . $column, (bool) $request->$column);
                }
            }
        }
        return $query;
    }

    protected function applyNullableFilters(Builder &$query, $request): Builder
    {
        if (! empty($this->filterNull)) {
            foreach ($this->filterNull as $column) {
                if ($request->has($column)) {
                    $method = $request->boolean($column) ? 'whereNull' : 'whereNotNull';
                    $query->$method($this->getTable() . '.' . $column);
                }
            }
        }
        return $query;
    }

    protected function applyCustomFilters(Builder &$query, $request): Builder
    {
        if (! empty($this->filterCustom)) {
            foreach ($this->filterCustom as $name => $handler) {
                if ($request->has($name)) {
                    $handler($query, $request->get($name));
                }
            }
        }
        return $query;
    }
    protected function applyRelationAndSearchFilters(Builder &$query, $request): Builder
    {

        if ($request->has('multiple_search') && ! empty($this->filterRelationAndCol)) {
            if (! empty($this->filterRelationAndCol)) {
                $search = $request->multiple_search;
                foreach ($this->filterRelationAndCol as $relation => $value) {
                    if (is_int($relation)) {
                        $query->where($this->getTable() . '.' . $value, 'LIKE', "%$search%");
                    } else {
                        $query->orwhereHas($relation, function ($q) use ($value, $search) {
                            $q->where($value, 'LIKE', "%$search%");
                        });
                    }

                }
            }
        }
        return $query;
    }
}
