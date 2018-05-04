<?
require_once(dirname(__FILE__) . '/./lib/OpenGraph.php');

if ($_SERVER['REQUEST_METHOD'] === "GET") {
	header("location:/404.php");
	exit;
} else if ($_SERVER['REQUEST_METHOD'] === "POST") {
	opg::post();
}

class opg {
	public static function post(){
		$post = $_POST;
		if (!isset($post['source'])) {
			header("location:/404.php");
			exit;
		};
		if (filter_var($post['source'], FILTER_VALIDATE_URL)
			&& preg_match('|^https?://.*$|', $post['source']))
		{
			$url = $post['source'];
		} else {
			header("location:/404.php");
			exit;			
		}

		$vals["number"] = $post["number"];
		$graph = OpenGraph::fetch($url);
		if (!is_null($graph->image)) {
			$vals["image"] = $graph->image;
			echo( json_encode($vals) );
		} else {
			$vals["image"] = "/images/noimage.gif";
			echo( json_encode($vals) );
		}
	}
}
