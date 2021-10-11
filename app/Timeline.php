<?php

namespace App;

use Eloquent as Model;
use Intervention\Image\Facades\Image;

//use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Timeline",
 *      required={},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="username",
 *          description="username",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="about",
 *          description="about",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="avatar_id",
 *          description="avatar_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="cover_id",
 *          description="cover_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="cover_position",
 *          description="cover_position",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
 *      )
 * )
 */
class Timeline extends Model
{
    //use SoftDeletes;
    protected $connection = 'mysql2';
    public $table = 'timelines';

}
