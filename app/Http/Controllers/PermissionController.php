<?php

namespace App\Http\Controllers;

use App\Models\AccessControl;
use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class PermissionController extends Controller {
    public function index($role_id = '') {
        $permission_list = array();

        if ($role_id != '') {
            $permission_list = AccessControl::where("role_id", $role_id)
                ->pluck('permission')
                ->toArray();
        }

        $ignoreRoute = array(
            'support_tickets.destroy',
        );

        $app = app();

        $routeCollection = $app->routes->getRoutes();

        $routes = [];

        // loop through the collection of routes
        foreach ($routeCollection as $route) {

            // get the action which is an array of items
            $action = $route->getAction();

            // if the action has the key 'controller'
            if (array_key_exists('controller', $action)) {

                if (!in_array("permission", $action['middleware'])) {
                    continue;
                }

                // explode the string with @ creating an array with a count of 2
                $explodedAction = explode('@', $action['controller']);

                if (!isset($routes[$explodedAction[0]])) {
                    $routes[$explodedAction[0]] = [];
                }

                if (isset($explodedAction[1]) && strpos($explodedAction[0], 'App') === 0) {
                    $test = new $explodedAction[0]();
                    if (method_exists($test, $explodedAction[1])) {
                        $routes[$explodedAction[0]][] = array("method" => $explodedAction[1], "action" => $route->action);
                    }
                }
            }
        }

        $permission = array();

        foreach ($routes as $key => $route) {
            foreach ($route as $r) {
                if (strpos($r['method'], 'get') === 0) {
                    continue;
                }

                if (array_key_exists('as', $r['action'])) {
                    $routeName = $r['action']['as'];
                    //If not needed so ignore
                    if (in_array($routeName, $ignoreRoute)) {
                        continue;
                    }
                    $permission[$key][$routeName] = $r['method'];
                }

            }
        }

        foreach ($permission as $key => $val) {
            foreach ($val as $name => $url) {
                if ($url == "store" && in_array("create", $val)) {
                    unset($permission[$key][$name]);
                }
                if ($url == "update" && in_array("edit", $val)) {
                    unset($permission[$key][$name]);
                }
            }
        }

        return view('backend.permission.create', compact('permission', 'permission_list', 'role_id'));

    }

    public function store(Request $request) {
        $this->validate($request, [
            'role_id'     => 'required',
            'permissions' => 'required',
        ]);

        DB::beginTransaction();

        $permission = AccessControl::where('role_id', $request->role_id);
        $permission->delete();

        foreach ($request->permissions as $role) {
            $permission             = new AccessControl();
            $permission->role_id    = $request->role_id;
            $permission->permission = $role;
            $permission->save();
        }

        DB::commit();

        return redirect()->route('permission.index')->with('success', _lang('Saved successfully'));

    }

}
