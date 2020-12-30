<?php

\FLBuilderCSS::border_field_rule( array(
    'settings'  => $settings,
    'setting_name'  => 'border',
    'selector'  => ".fl-node-$id .my-selector",
) );

FLBuilderCSS::typography_field_rule( array(
  'settings'    => $settings,
  'setting_name'    => 'my_typography',
  'selector'    => ".fl-node-$id .my-selector",
) );