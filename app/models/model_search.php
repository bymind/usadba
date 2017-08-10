<?php

class Model_Search extends Model
{
	public $pageDataModel;

	function searchType($search, $col)
	{
		$result = array();
		if (is_array($search)) {
			Route::Catch_301_Redirect("/search/".$search['name']);
		}
		$search = urldecode(urldecode($search));
		$search = explode(' ', $search);
		$temp_by_name = array();
		if ($col=="description") {
			$q = mysql_query("SELECT * FROM prod_items WHERE ($col LIKE '%".$search[0]."%' OR mini_desc LIKE '%".$search[0]."%' ) AND archived = 0") or die(mysql_query());
		} else
		$q = mysql_query("SELECT * FROM prod_items WHERE $col LIKE '%".$search[0]."%' AND archived = 0") or die(mysql_query());
		$i = 1;
		while ($buf = mysql_fetch_assoc($q)) {
			$temp_by_name[$i] = $buf['id'];
			$i++;
		}
		for ($i=1; $i <= count($search); $i++) {
			for ($j=1; $j <= count($temp_by_name) ; $j++) {
				if ($col=="description") {
					$q = mysql_query("SELECT * FROM prod_items WHERE id = ".$temp_by_name[$j]." AND ( $col LIKE '%".$search[$i]."%' OR mini_desc LIKE '%".$search[$i]."%' ) AND archived = 0") or die(mysql_query());
				} else
				$q = mysql_query("SELECT * FROM prod_items WHERE id = ".$temp_by_name[$j]." AND $col LIKE '%".$search[$i]."%' AND archived = 0") or die(mysql_query());
				$row = mysql_fetch_assoc($q);
				if ($row['id']!=$temp_by_name[$j]) {
					$temp_by_name[$j] = -1;
				}
			}
		}
		$temp_by_name_str = "";
		for ($i=1; $i <= count($temp_by_name); $i++) {
			if ($temp_by_name[$i]>0) {
				if ($i==1) {
					$temp_by_name_str .= $temp_by_name[$i];
				} else {
					$temp_by_name_str .= "','".$temp_by_name[$i];
				}
			}
		}
		$q = mysql_query("SELECT * FROM prod_items WHERE id in ('$temp_by_name_str') AND archived = 0") or die(mysql_query());
		$search_res = array();
		while ($buf = mysql_fetch_assoc($q)) {
			$search_res[] = $buf;
		}
		$search_res = Model::createProdUrl($search_res);
		$search_res = Model::createInStock($search_res);
		return $search_res;
	}

	public function getSearchData($search)
	{
		$pageDataModel['search']['by_art'] = Self::searchType($search, "art");
		$pageDataModel['search']['by_name'] = Self::searchType($search, "name");
		$pageDataModel['search']['by_desc'] = Self::searchType($search, "description");
		$pageDataModel['title'] = "Результаты поиcка";
		$pageDataModel['title_header'] = "Результаты поиcка <span class='search-query'>".urldecode(urldecode($search))."</span>";
		return $pageDataModel;
	}
}