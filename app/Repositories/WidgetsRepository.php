<?php namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class WidgetsRepository {
    public function showGrid(Model $model, array $config, array $params){
        $config = $config+[
            'actionTitle' => trans('widget.grid.actionTitle'),
            'actionEditTitle' => trans('widget.grid.actionEditTitle'),
            'actionDeleteTitle' => trans('widget.grid.actionDeleteTitle'),
            'emptyText' => trans('widget.grid.empty'),
            'items' => $model->sorting($params, isset($config['sortColumn'])?$config['sortColumn']:'id', isset($config['sortDirection'])?$config['sortDirection']:false),
            'params' => ['host'=>$config['host']]+$params,
        ];

        if(isset($config['with']))
            $config['items'] = $config['items']->with($config['with']);
        $config['items'] = $config['items']->paginate($config['itemCount'])->appends($params);

        return view('erp.widget.grid.index', compact('model'))->with($config);

    }
}