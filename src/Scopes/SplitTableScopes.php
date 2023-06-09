<?php

namespace Aphly\Laravel\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SplitTableScopes implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $query = $builder->getQuery();
        $from = $query->from;
        if (strpos($from, "{SPLIT_TABLE}") === false) {
            return;
        }
        $query->from = null;
        $splitFrom = str_replace("{SPLIT_TABLE}", "", $from);
        $splitTables = explode("{SPLIT_TABLE_FLAG}", $splitFrom);
        $wheres = $query->wheres;
        $columns = empty($query->columns) ? "*" : $query->columns;
        $bindings = empty($query->bindings) ? [] : $query->bindings;
        $myBindings = [];
        $queries = collect();
        foreach ($splitTables as $table) {
            $tempDb = DB::table($table);
            $tempBindings = [];
            foreach ($wheres as $where) {
                $tempDb->where($where['column'], $where['operator'], $where['value'], $where['boolean']);
                $tempBindings[] = $where['value'];
            }
            $myBindings = array_merge($myBindings, $tempBindings);
            $queries->push($tempDb->select($columns));
        }
        $firstQuery = $queries->shift();
        $queries->each(function ($item, $key) use ($firstQuery) {
            $firstQuery->unionAll($item);
        });
        $bindings = array_merge($myBindings, $bindings);
        $query->bindings = $bindings;
        $query->from = DB::raw("({$firstQuery->toSql()}) as table_all");
    }

}
