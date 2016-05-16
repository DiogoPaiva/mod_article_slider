<?php

defined('_JEXEC') or die('Restricted access');
require_once JPATH_SITE . '/components/com_content/helpers/route.php';
jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
 function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
	} 

abstract class modKlixoArticlesSliderHelper {
  
	
	public static function getContentList(&$params) {

	// Set application parameters in model
	$app = JFactory::getApplication();
	$appParams = $app->getParams();	
	$tipo = $params->get("featured", '');
		
		
		
		//Para Categorias
		if($tipo =="child_c")
		{
			//Get Categories Model - Diogo
			$model = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
			$model->setState('params', $appParams);
			$model->setState('filter.categories', $params->get('featured', "child_c"));
			
			// Access filter
			$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
			$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
			$model->setState('filter.access', $access);

			// Category filter
			$model->setState('filter.parentId', $params->get('categoryId', array()));

			// Filter by language
			$model->setState('filter.language', $app->getLanguageFilter());
			$catID = $params->get('categoryId', value);
			$categories = JCategories::getInstance('Content');
			$model = $categories->get($catID);
			$items = $model->getChildren();
		
			foreach ($items as &$item) {
			  
				$item->content = ($params->get('show_img') == '0' || $params->get('reformat_content') == '1' ) ? modKlixoArticlesSliderHelper::stripTags($item->introtext) : $item->introtext;
				$item->slug = $item->id . ':' . $item->alias;
				$item->catslug = $item->catid . ':' . $item->category_alias;
				$item->link = JRoute::_(ContentHelperRoute::getCategoryRoute($item->slug, $item->catslug));
				
				if (!isset($item->sub_title)) {
					$item->sub_title = modKlixoArticlesSliderHelper::cutStr($item->title, $params->get('limittitle', 25));
				}
				if (!isset($item->sub_content)) {
				if ($params->get('reformat_content') == 1) {
						$item->sub_content = modKlixoArticlesSliderHelper::cutStr($item->content, $params->get('limit_description'));
					} else {
						$item->sub_content = $item->content;
					}
				} 
			}
			return $items;
		}
			
		
		//Para Artigos
		if($tipo =="show")
		{
			// Get an instance of the generic articles model
			$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
			$model->setState('params', $appParams);
			
			// Set the filters based on the module params
			$model->setState('list.start', 0);
			$model->setState('list.limit', (int) $params->get('count', 5));
			$model->setState('filter.published', 1);
			$model->setState('filter.featured', $params->get('featured', "show"));

			// Access filter
			$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
			$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
			$model->setState('filter.access', $access);

			// Category filter
			$model->setState('filter.category_id', $params->get('categoryId', array()));

			// Filter by language
			$model->setState('filter.language', $app->getLanguageFilter());

			// Ordering
			$ordering = $params->get('ordering', 'a.ordering');
			$model->setState('list.ordering', $ordering);
			if (trim($ordering) == 'rand()') {
				$model->setState('list.direction', '');
			} else {
				$model->setState('list.direction', $params->get('sort_order', "ASC"));
			}

			$items = $model->getItems();
			
			
			foreach ($items as &$item) {
				$item->content = ($params->get('show_img')== '0' || $params->get('reformat_content') == '1' ) ? modKlixoArticlesSliderHelper::stripTags($item->introtext) : $item->introtext;
				$item->slug = $item->id . ':' . $item->alias;
				$item->catslug = $item->catid . ':' . $item->category_alias;
				
				
				if ($access || in_array($item->access, $authorised)) {
					// We know that user has the privilege to view the article
					$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
				} else {
					$item->link = JRoute::_('index.php?option=com_users&view=login');
				}
				if (!isset($item->sub_title)) 
				{
					$item->sub_title = modKlixoArticlesSliderHelper::cutStr($item->title, $params->get('limittitle', 25));
				}
				if (!isset($item->sub_content)) 
				{
				if ($params->get('reformat_content') == 1) 
				{
					$item->sub_content =  modKlixoArticlesSliderHelper::cutStr($item->content, $params->get('limit_description'));
					} else {
						$item->sub_content = $item->content;
					}
				}
				
			}
			return $items;
		}
		
	}
	
    function stripTags($str) {
        $regex = array("/\<img[^\>]*>/", "/<div class=\"mosimage\".*<\/div>/", "/<object[0-9 a-z_?*=\"&\;\:\-\/\.#\,<>\\n\\r\\t]+<\/object>/smi", "/<iframe[0-9 a-z_?*=\"&\;\:\-\/\.#\,<>\\n\\r\\t]+<\/iframe>/smi");
        $str = preg_replace($regex, '', $str);
        return $str;
    }

    static function cutStr($str, $number) {
		//If length of string less than $number
        $str = strip_tags($str);
        if (strlen($str) <= $number) {
            return $str;
        }
        $str = substr($str, 0, $number);
        $pos = strrpos($str, ' ');
        return substr($str, 0, $pos) . ' ...';
    }
}
?>