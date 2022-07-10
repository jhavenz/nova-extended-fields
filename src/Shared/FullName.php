<?php

namespace Jhavenz\NovaExtendedFields\Shared;

use Jhavenz\NovaExtendedFields\Concerns\ExtendedNovaField;
use Laravel\Nova\Fields\Field;

class FullName extends Field
{
    use ExtendedNovaField;

    //public function __construct($name = null, $attribute = null, callable $resolveCallback = null)
    //{
    //    parent::__construct(
    //        $name ?? 'Name',
    //        $attribute ?? 'full_name',
    //        $resolveCallback
    //    );
    //}
}
