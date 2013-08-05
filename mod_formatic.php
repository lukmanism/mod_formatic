<?php
/**
 *
 *
 * @package   mod_formatic
 * copyright Lukman Hussein
 * @license GPL3
 */

// no direct access
defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$style = $params->get('style');
require JModuleHelper::getLayoutPath('mod_formatic', $params->get('layout', 'default'));

