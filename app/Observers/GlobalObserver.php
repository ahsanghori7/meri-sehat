<?php

namespace App\Observers;

use App\Models\LogActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class GlobalObserver
{
    public function chk_module() {
        $module = Request::segment(2) ? ucwords(str_replace('-', ' ', Request::segment(2))) : '';
        return $module;
    }

    public function updated(Model $model)
    {
        // ...
        if (str_contains(Request::fullUrl(), 'admin')) {
            $log = [];
            $log['subject'] = auth()->user()->name . " (" . auth()->user()->id . ") has been updated a " . $this->chk_module();
            $log['url'] = Request::fullUrl();
            $log['method'] = Request::method();
            $log['event'] = 'update';
            $log['old_value'] = json_encode($model->getOriginal());
            $log['new_value'] = json_encode($model->getAttributes());
            $log['module'] = $this->chk_module();
            $log['table'] = $model->getTable();
            $log['ip'] = Request::ip();
            $log['agent'] = Request::header('user-agent');
            $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
            LogActivity::create($log);
        }
    }

    public function created(Model $model)
    {
        // ...
        if (str_contains(Request::fullUrl(), 'admin')) {
            $log = [];
            $log['subject'] = auth()->user()->name . " (" . auth()->user()->id . ") has been created a new " . $this->chk_module();
            $log['url'] = Request::fullUrl();
            $log['method'] = Request::method();
            $log['event'] = 'add';
            $log['old_value'] = json_encode($model->getAttributes());
            $log['new_value'] = json_encode($model->getAttributes());
            $log['module'] = $this->chk_module();
            $log['table'] = $model->getTable();
            $log['ip'] = Request::ip();
            $log['agent'] = Request::header('user-agent');
            $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
            LogActivity::create($log);
        }
    }

    public function deleted(Model $model)
    {
        // ...
        if (str_contains(Request::fullUrl(), 'admin')) {
            $log = [];
            $log['subject'] = auth()->user()->name . " (" . auth()->user()->id . ") has been deleted " . $this->chk_module();
            $log['url'] = Request::fullUrl();
            $log['method'] = Request::method();
            $log['event'] = 'delete';
            $log['old_value'] = json_encode($model->getOriginal());
            $log['new_value'] = json_encode($model->getAttributes());
            $log['module'] = $this->chk_module();
            $log['table'] = $model->getTable();
            $log['ip'] = Request::ip();
            $log['agent'] = Request::header('user-agent');
            $log['user_id'] = auth()->check() ? auth()->user()->id : 1;
            LogActivity::create($log);
        }
    }

    public function forceDeleted(Model $model)
    {
        // ...
    }
}
