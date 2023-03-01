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
    public static function create_query($dataModel, $data)
    {
        try {
            $create = $dataModel::create($data);
            return $create;
        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401
            );
        }
    }
}
