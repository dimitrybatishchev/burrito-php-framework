<?php
/**
 * Created by PhpStorm.
 * User: dimit_000
 * Date: 12/4/13
 * Time: 9:48 AM
 */

namespace Burrito\Framework\Form;


class Form
{
    private static $instance = NULL;
    public static function getInstance()

    {

        if (self::$instance === NULL)

        {

            self::$instance = new self;

        }

        return self::$instance;

    }



    // render <form> opening tag

    public static function open(array $attributes)

    {

        $html = '<form';

        if (!empty($attributes))

        {

            foreach ($attributes as $attribute => $value)

            {

                if (in_array($attribute, array('action', 'method', 'id', 'class', 'enctype')) and !empty($value))

                {

                    // assign default value to 'method' attribute

                    if ($attribute === 'method' and ($value !== 'post' or $value !== 'get'))

                    {

                        $value = 'post';

                    }

                    $html .= ' ' . $attribute . '="' . $value . '"';

                }

            }

        }

        return $html . '>';

    }



    // render <input> tag

    public static function input(array $attributes)

    {

        $html = '<input';

        if (!empty($attributes))

        {

            foreach ($attributes as $attribute => $value)

            {

                if (in_array($attribute, array('type', 'id', 'class', 'name', 'value')) and !empty($value))

                {

                    $html .= ' ' . $attribute . '="' . $value . '"';

                }

            }

        }

        return $html . '>';

    }



    // render </form> closing tag

    public static function close()

    {

        return '</form>';

    }
}