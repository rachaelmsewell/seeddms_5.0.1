<?php
/**
 * Implementation of DefaultKeywords view
 *
 * @category   DMS
 * @package    SeedDMS
 * @license    GPL 2
 * @version    @version@
 * @author     Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */

/**
 * Include parent class
 */
require_once("class.Bootstrap.php");

/**
 * Class which outputs the html page for DefaultKeywords view
 *
 * @category   DMS
 * @package    SeedDMS
 * @author     Markus Westphal, Malcolm Cowe, Uwe Steinmann <uwe@steinmann.cx>
 * @copyright  Copyright (C) 2002-2005 Markus Westphal,
 *             2006-2008 Malcolm Cowe, 2010 Matteo Lucarelli,
 *             2010-2012 Uwe Steinmann
 * @version    Release: @package_version@
 */
class SeedDMS_View_DefaultKeywords extends SeedDMS_Bootstrap_Style {

	function js() { /* {{{ */
		header('Content-Type: application/javascript');
?>
function checkForm()
{
	msg = new Array();

	if($("#form .name").val() == "") msg.push("<?php printMLText("js_no_name");?>");
	if (msg != "")
	{
  	noty({
  		text: msg.join('<br />'),
  		type: 'error',
      dismissQueue: true,
  		layout: 'topRight',
  		theme: 'defaultTheme',
			_timeout: 1500,
  	});
		return false;
	}
	else
		return true;
}

function checkFormName()
{
	msg = new Array();

	if($(".formn .name").val() == "") msg.push("<?php printMLText("js_no_name");?>");
	if (msg != "")
	{
  	noty({
  		text: msg.join('<br />'),
  		type: 'error',
      dismissQueue: true,
  		layout: 'topRight',
  		theme: 'defaultTheme',
			_timeout: 1500,
  	});
		return false;
	}
	else
		return true;
}

function checkKeywordForm()
{
	msg = new Array();

	if($(".formk .keywords").val() == "") msg.push("<?php printMLText("js_no_name");?>");
	if (msg != "")
	{
  	noty({
  		text: msg.join('<br />'),
  		type: 'error',
      dismissQueue: true,
  		layout: 'topRight',
  		theme: 'defaultTheme',
			_timeout: 1500,
  	});
		return false;
	}
	else
		return true;
}

$(document).ready( function() {
	$('body').on('submit', '#form', function(ev){
		if(checkForm()) return;
		event.preventDefault();
	});
	$('body').on('submit', '.formk', function(ev){
		if(checkKeywordForm()) return;
		event.preventDefault();
	});
	$('body').on('submit', '.formn', function(ev){
		if(checkFormName()) return;
		event.preventDefault();
	});
	$( "#selector" ).change(function() {
		$('div.ajax').trigger('update', {categoryid: $(this).val()});
	});
});
<?php
	} /* }}} */

	function form() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$category = $dms->getKeywordCategory($this->params['selcategoryid']);

		$this->showKeywordForm($category, $user);
	} /* }}} */

	function showKeywordForm($category, $user) { /* {{{ */
		if(!$category) {
?>
			
			<form class="form-inline" action="../op/op.DefaultKeywords.php" method="post" id="form">
  		<?php echo createHiddenFieldWithKey('addcategory'); ?>
			<input type="hidden" name="action" value="addcategory">
			<?php printMLText("name");?>: <input type="text" class="name" name="name">
			<input type="submit" class="btn" value="<?php printMLText("new_default_keyword_category"); ?>">
			</form>
<?php
		} else {
			$owner = $category->getOwner();
			if ((!$user->isAdmin()) && ($owner->getID() != $user->getID())) return;
?>
			<table class="table-condensed">
				<tr>
					<td></td>
					<td>
						<form action="../op/op.DefaultKeywords.php" method="post">
  						<?php echo createHiddenFieldWithKey('removecategory'); ?>
							<input type="Hidden" name="action" value="removecategory">
							<input type="Hidden" name="categoryid" value="<?php echo $category->getID()?>">
							<button type="submit" class="btn" title="<?php echo getMLText("delete")?>"><i class="icon-remove"></i> <?php printMLText("rm_default_keyword_category");?></button>
						</form>
					</td>
				</tr>
				<tr>
					<td><?php echo getMLText("name")?>:</td>
					<td>
						<form class="form-inline formn" action="../op/op.DefaultKeywords.php" method="post">
  						<?php echo createHiddenFieldWithKey('editcategory'); ?>
							<input type="hidden" name="action" value="editcategory">
							<input type="hidden" name="categoryid" value="<?php echo $category->getID()?>">
							<input name="name" class="name" type="text" value="<?php echo htmlspecialchars($category->getName()) ?>">
							<button type="submit" class="btn"><i class="icon-save"></i> <?php printMLText("save");?></button>
						</form>
					</td>
				</tr>
				<tr>
					<td><?php echo getMLText("default_keywords")?>:</td>
					<td>
						<?php
							$lists = $category->getKeywordLists();
							if (count($lists) == 0)
								print getMLText("no_default_keywords");
							else
								foreach ($lists as $list) {
?>
									<form class="form-inline formk" style="display: inline-block;" method="post" action="../op/op.DefaultKeywords.php">
  								<?php echo createHiddenFieldWithKey('editkeywords'); ?>
									<input type="Hidden" name="categoryid" value="<?php echo $category->getID()?>">
									<input type="Hidden" name="keywordsid" value="<?php echo $list["id"]?>">
									<input type="Hidden" name="action" value="editkeywords">
									<input name="keywords" class="keywords" type="text" value="<?php echo htmlspecialchars($list["keywords"]) ?>">
									<button class="btn" title="<?php echo getMLText("save")?>"><i class="icon-save"></i> <?php echo getMLText("save")?></button>
									<!--	 <input name="action" value="removekeywords" type="Image" src="images/del.gif" title="<?php echo getMLText("delete")?>" border="0"> &nbsp; -->
									</form>
									<form style="display: inline-block;" method="post" action="../op/op.DefaultKeywords.php" >
  								<?php echo createHiddenFieldWithKey('removekeywords'); ?>
									<input type="hidden" name="categoryid" value="<?php echo $category->getID()?>">
									<input type="hidden" name="keywordsid" value="<?php echo $list["id"]?>">
									<input type="hidden" name="action" value="removekeywords">
									<button class="btn" title="<?php echo getMLText("delete")?>"><i class="icon-remove"></i> <?php echo getMLText("delete")?></button>
									</form>
									<br>
						<?php }  ?>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
					  <form class="form-inline formk" action="../op/op.DefaultKeywords.php" method="post">
  				  <?php echo createHiddenFieldWithKey('newkeywords'); ?>
						<input type="Hidden" name="action" value="newkeywords">
						<input type="Hidden" name="categoryid" value="<?php echo $category->getID()?>">
						<input type="text" class="keywords" name="keywords">
					
					  <input type="submit" class="btn" value="<?php printMLText("new_default_keywords");?>">
						</form>
					</td>
				</tr>

			</table>
<?php
		}
	} /* }}} */

	function show() { /* {{{ */
		$dms = $this->params['dms'];
		$user = $this->params['user'];
		$categories = $this->params['categories'];
		$selcategoryid = $this->params['selcategoryid'];

		$this->htmlStartPage(getMLText("admin_tools"));
		$this->globalNavigation();
		$this->contentStart();
		$this->pageNavigation(getMLText("admin_tools"), "admin_tools");

		$this->contentHeading(getMLText("global_default_keywords"));
?>
<div class="row-fluid">
<div class="span4">
<div class="well">
	<?php echo getMLText("selection")?>:
	<select id="selector" class="span9">
		<option value="-1"><?php echo getMLText("choose_category")?>
		<option value="0"><?php echo getMLText("new_default_keyword_category")?>
<?php
				
		$selected=0;
		$count=2;				
		foreach ($categories as $category) {
		
			$owner = $category->getOwner();
			if ((!$user->isAdmin()) && ($owner->getID() != $user->getID())) continue;

			if ($selcategoryid && $category->getID()==$selcategoryid) $selected=$count;				
			print "<option value=\"".$category->getID()."\">" . htmlspecialchars($category->getName());
			$count++;
		}
?>
			</select>
</div>
</div>

<div class="span8">
	<div class="well">
		<div class="ajax" data-view="DefaultKeywords" data-action="form" <?php echo ($selcategoryid ? "data-query=\"categoryid=".$selcategoryid."\"" : "") ?>></div>
		</div>
	</div>
</div>

<?php
		$this->htmlEndPage();
	} /* }}} */
}
?>
