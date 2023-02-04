<?php
namespace common\components\widgets;

use \yii\widgets\DetailView;

class ResponsiveDetailView extends DetailView
{
    /**
     * @var string|callable the template used to render a single attribute. If a string, the token `{label}`
     * and `{value}` will be replaced with the label and the value of the corresponding attribute.
     * If a callback (e.g. an anonymous function), the signature must be as follows:
     *
     * ~~~
     * function ($attribute, $index, $widget)
     * ~~~
     *
     * where `$attribute` refer to the specification of the attribute being rendered, `$index` is the zero-based
     * index of the attribute in the [[attributes]] array, and `$widget` refers to this widget instance.
     */
    public $template = '<div class="col-md-3"><p class="titulo">{label}</p><p class="info">{value}</p></div>';
    /**
     * @var array the HTML attributes for the container tag of this widget. The "tag" option specifies
     * what container tag should be used. It defaults to "table" if not set.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'row', 'tag' => 'div'];
}
