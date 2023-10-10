<?php

/**
 * @package php-svg-lib
 * @link    http://github.com/PhenX/php-svg-lib
 * @author  Fabien Ménager <fabien.menager@gmail.com>
 * @license GNU LGPLv3+ http://www.gnu.org/copyleft/lesser.html
 */

namespace Svg\Tag;

use Encore\Admin\Auth\Database\Administrator;
use Svg\Style;

class Group extends AbstractTag
{
    protected function before($attributes)
    {
        $surface = $this->document->getSurface();

        $surface->save();

        $style = $this->makeStyle($attributes);

        $this->setStyle($style);
        $surface->setStyle($style);

        $this->applyTransform($attributes);
    }

    //getter for registration_year
    public function getRegistrationYearAttribute($value)
    {
        //counte memebrs in this group 
        $members = Administrator::where('group_id', $this->id)->get();
        return $members;
    }


    protected function after()
    {
        $this->document->getSurface()->restore();
    }
}
