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

class DBHelpers
{
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

    public static function update_query($dataModel, $data, $id)
    {
        try {
            return $dataModel::where('id', $id)->update($data);
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }

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
