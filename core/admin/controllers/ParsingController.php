<?php


namespace core\admin\controllers;


use core\base\controllers\BaseMethod;

class ParsingController extends BaseAdmin
{
	use BaseMethod;

	protected $linkArr = [];

	protected $parsingLogFile = 'parsing_log.txt';
	protected $fileArr = ['jpg', 'png', 'jpeg', 'gif', 'xls', 'xlsx', 'pdf', 'mp4', 'mpeg', 'mp3'];

	protected $filterArr = [
		'url' => [],
		'get' => []
	];

	protected function inputData($links_counter = 1)
	{

		if (!function_exists('curl_init')) {
			$this->writeLog('Отсутсвует библиотека CURL');
			$_SESSION['res']['answer'] = '<div class="error">Library CURL</div>';
			$this->redirect();
		}

		set_time_limit(0);

		if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile)) ;
		@unlink($_SERVER['DOCUMENT_ROOT'] . PATH . 'log/' . $this->parsingLogFile);

		$this->parsing(SITE_URL);

		$this->createSitemap();

		!$_SESSION['res']['answer'] && $_SESSION['res']['answer'] = '<div class="success">Sitemap is created</div>';

		$this->redirect();
	}

	protected function parsing($url, $index = 0)
	{
		if ($url === '/' || $url === SITE_URL.'/') return;

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 120);
		curl_setopt($curl, CURLOPT_RANGE, 0 - 4194304);

		$out = curl_exec($curl);

		curl_close($curl);

		if (!preg_match('/Content-Type:\s+text\/html/ui', $out)) {
			unset($this->linkArr[$index]);
			$this->linkArr = array_values($this->linkArr);
			return;
		}

		if (!preg_match('/HTTP\/\d\.?\d?\s+20\d/ui', $out)) {

			$this->writeLog('Не корректная ссылка - '.$url, $this->parsingLogFile);
			unset($this->linkArr[$index]);

			$this->linkArr = array_values($this->linkArr);

		}

		preg_match_all('/<a\s*?[^>]*?href\s*?=(["\'])(.*?)\1[^>]*?>/ui', $out, $links);

		if ($links[2]) {

			foreach ($links[2] as $link) {

				if ($link === '/' || $link === SITE_URL . '/') continue;
				//if ($link === "") continue;

				foreach ($this->fileArr as $ext) {
					if ($ext) {
						$ext = addslashes($ext);
						$ext = str_replace('.', '\.', $ext);

						if (preg_match('/' . $ext . '(\s*?$|\?[^\/]*$)/ui', $link)) {
							continue 2;
						}
					}
				}


				if (strpos($link, '/') === 0) {
					$link = SITE_URL . $link;
				}

				if (!in_array($link, $this->linkArr) && $link !== '#' && strpos($link, SITE_URL) === 0) {
					if ($this->filter($link)) {
						$this->linkArr[] = $link;
						$this->parsing($link, count($this->linkArr) - 1);
					}
				}
			}

		}
	}


	protected function filter($link)
	{

		if ($this->filterArr) {
			foreach ($this->filterArr as $type => $values) {
				if ($values) {
					foreach ($values as $item) {
						$item = str_replace('/', '\/', addslashes($item));

						if ($type === 'url') {
							if (preg_match('/^[^\?]*' . $item . '/ui', $link))
								return false;

						}

						if ($type === 'get') {
							if (preg_match('/(\?|&amp;|=|&)' . $item . '(=|&amp;|&|$)/ui', $link, $matches))
								return false;
						}
					}
				}
			}
		}
		return true;
	}



	protected function createSitemap()
	{
		$dom = new \domDocument('1.0','utf-8');
		$dom->formatOutput = true;

		$root = $dom->createElement('urlset');
		$root->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
		$root->setAttribute('xmlns:xls','http://w.3.org/2001/XMLSchema-instance');
		$root->setAttribute('xsi:schemaLocation','http://www.sitemaps.org/schemas/sitemap/0.9 http://http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

		$dom->appendChild($root);

		$sxe = simplexml_import_dom($dom);

		if ($this->linkArr){

			$date = new \DateTime();
			$lastMod = $date->format('Y-m-d ').'T '.$date->format('H:i:s');

			$c=1;
			foreach ($this->linkArr as $item){
				$elem = trim(mb_substr($item, mb_strlen(SITE_URL)),'/');
				$elem = explode('/',$elem);

				$count = '0.'. (count($elem) -1);
				$priority = 1 - (float)$count;

				if ($priority == 1) $priority = '1.0';

				$urlMain = $sxe->addChild('url');
				$urlMain->addChild('loc', htmlspecialchars($item));

				$urlMain->addChild('lastmod',$lastMod);
				$urlMain->addChild('changefreq','weekly');
				$urlMain->addChild('priority',$priority);

				echo $c.'. '. $item.'<br>';
				$c++;
			}
		}
		$dom->save($_SERVER['DOCUMENT_ROOT'].PATH.'sitemap.xml');
	}

}