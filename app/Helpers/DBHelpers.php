<?php /**
 *
 *
 * @package
 * @author	School Mgt
 * @copyright
 * @version	1.0.0
 */

namespace App\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DBHelpers
{
    //////  query where filter first data
    public static function query_filter_first($dataModel, $filter)
    {
        try {
            return $dataModel
                ::where($filter)
                ->get()
                ->first();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    public static function insert_update_query($table, $where, $data)
    {
        try {
            return DB::table($table)->updateOrInsert($where, $data);
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    // catch (\Illuminate\Database\QueryException $e) {
    //     if ($e->getCode() === '23000') { // integrity constraint violation
    //         return back()->withError('Invalid data');
    //     }
    // }

    public static function count($dataModel, $data = null)
    {
        try {
            if ($data == null) {
                return $dataModel::count();
            }
            return $dataModel
                ::query()
                ->where($data)
                ->count();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    public static function exists($dataModel, $data)
    {
        try {
            return $dataModel
                ::query()
                ->where($data)
                ->exists();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    public static function init_model($dataModel)
    {
        try {
            return $dataModel::query();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    public static function with_query($dataModel, $with_clause = [])
    {
        try {
            return $dataModel
                ::query()
                ->with($with_clause)
                ->get();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    public static function with_where_query_filter(
        $dataModel,
        $with_clause = [],
        $where = null
    ) {
        try {
            if ($where == null) {
                return $dataModel
                    ::query()
                    ->with($with_clause)
                    ->get();
            } else {
                return $dataModel
                    ::query()
                    ->with($with_clause)
                    ->where($where)
                    ->get();
            }
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    //////  query where filter data
    public static function query_filter($dataModel, $filter)
    {
        try {
            return $dataModel::where($filter)->get();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    ////// get first query data
    public static function first_data($dataModel)
    {
        try {
            return $dataModel::first();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    ////// get all query data
    public static function all_data($dataModel)
    {
        try {
            return $dataModel::all();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    /////// Delete query data
    public static function delete_query($dataModel, $id)
    {
        try {
            return $dataModel::where('id', $id)->delete();
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    /////// Update query data
    public static function update_query($dataModel, $data, $id = 0)
    {
        try {
            if ($id == null) {
                return $dataModel::query()->update($data);
            } else {
                return $dataModel::where('id', $id)->update($data);
            }
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

    //////// Insert query data /////////
    public static function create_query($dataModel, $data)
    {
        try {
            return $dataModel::create($data);
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }
}
