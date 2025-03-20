<?php
/**
 * DokuWiki Plugin stlviewer (Syntax Component)
 *
 * @author  Damien Degois <damien@degois.info>
 * @license MIT
 *
 *  Options are URL encoded a=1&b=2 etc
 *  - s: size in pixel (defines height and width in one call)
 *  - h: height in pixel
 *  - w: width in pixel
 *  - color: color of the object
 *  - bgcolor: background color
 *  - display: Model shading to display (String: "flat" / "smooth"/ "wireframe", Default: "flat")
 *
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');
require_once DOKU_PLUGIN . 'syntax.php';

class syntax_plugin_stlviewer extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'substition';
    }

    public function getPType() {
        return 'normal';
    }

    public function getSort() {
        return 302;
    }

    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('\{\{[^\}]*?(?:\.stl)[^\}]*?\}\}', $mode, 'plugin_stlviewer');
    }

    /**
     * handle syntax
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {

        $opts = array( // set default
                       'id'      => '',
                       'pos'     => $pos,
                       'title'   => '',
                       'width'   => '500px',
                       'height'  => '500px',
                       'bgcolor' => '#aaaaaa',
                       'color'   => '#be5050',
                       'display' => 'flat',
        );

        $cleanmatch = trim($match, '{}');
        if (strpos($cleanmatch, ">") === false) {
            $params="stlviewer";
            $media = $cleanmatch;
        } else {
            list($params, $media) = explode('>', $cleanmatch, 2);
        }

        // handle media parameters (linkId and title)
        list($link, $title) = explode('|', $media, 2);

        list($id, $args) = explode('?', $link, 2);
        $args = trim($args);
        parse_str($args, $pargs);

        // msg('stlviewer args: ' . print_r($pargs,1), 1);

        if (isset($pargs['s'])) {
            $opts['width'] = $pargs['s'];
            $opts['height'] = $pargs['s'];
        }
        if (isset($pargs['h'])) {
            $opts['height'] = $pargs['h'];
        }
        if (isset($pargs['w'])) {
            $opts['width'] = $pargs['w'];
        }
        if (isset($pargs['noop'])) {
            $opts['noop'] = true;
        }
        if (isset($pargs['manual'])) {
            $opts['manual'] = true;
        }
        if (isset($pargs['display'])) {
            $opts['display'] = $pargs['display'];
        }
        
        foreach (['bgcolor', 'color'] as $k) {
            if (isset($pargs[$k]) && $pargs[$k] != "") {
                $opts[$k] = $pargs[$k];
            }
        }

        //add default px unit
        if(is_numeric($opts['width'])) $opts['width'] .= 'px';
        if(is_numeric($opts['height'])) $opts['height'] .= 'px';

        $opts['id'] = trim($id);
        if ($opts['title'] == "") {
            $opts['title'] = $id;
        }
        if (!empty($title)) {
            $opts['title'] = trim($title);
        }

        return array($state, $opts);
    }

    public function render($format, Doku_Renderer $renderer, $data) {

        if ($format != 'xhtml') {
            return false;
        }

        list($state, $opts) = $data;
        if ($opts['id'] == '') {
            return false;
        }

        $mediaurl = DOKU_URL . "lib/exe/fetch.php?media=" . $opts['id'];
        if (isset($opts['noop'])) {
            $renderer->doc .= "<a href=\"" . $mediaurl . "\">".$opts['id']."</a>";
            return false;
        }

        $buff = array();
        $buff[] = "<b>".$opts['title']."</b><br/>";
        $buff[] = "<div id=\"stl_cont".$opts['pos']."\" class=\"media\">";
        $buff[] = "<a href=\"javascript:init_stl_".$opts['pos']."()\">Show stl</a>";
        $buff[] = "</div>";
        $buff[] = "<script>";
        $buff[] = "function init_stl_".$opts['pos']."() {";
        $buff[] = "  var destdiv = document.getElementById(\"stl_cont".$opts['pos']."\");";
        $buff[] = "  destdiv.innerHTML = \"\";";
        $buff[] = "  destdiv.style.height = \"".$opts['height']."\";";
        $buff[] = "  destdiv.style.width = \"".$opts['width']."\";";
        $buff[] = "  var destdiv = document.getElementById(\"stl_cont".$opts['pos']."\");";
        $buff[] = "  var stl_viewer".$opts['pos']."=new StlViewer(";
        $buff[] = "    destdiv,";
        $buff[] = "    {";
        $buff[] = "      load_three_files: \"" . DOKU_URL . "lib/plugins/stlviewer/stlviewer/\",";
        $buff[] = "      auto_rotate: false,";
        $buff[] = "      controls: 1,";
        $buff[] = "      cameray: 100,";
        $buff[] = "      canvas_width: \"".$opts['width']."\",";
        $buff[] = "      canvas_height: \"".$opts['height']."\",";
        $buff[] = "      bg_color: \"".$opts['bgcolor']."\",";
        $buff[] = "      models: [ {";
        $buff[] = "        id:0,";
        $buff[] = "        color: \"".$opts['color']."\",";
        $buff[] = "        display: \"".$opts['display']."\",";
        $buff[] = "        filename: \"" . $mediaurl . "\"";
        $buff[] = "      } ]";
        $buff[] = "    }";
        $buff[] = "  );";
        $buff[] = "}";
        if (!$opts['manual']) {
            $buff[] = "document.addEventListener(\"DOMContentLoaded\", init_stl_".$opts['pos'].");";
        }
        $buff[] = "";
        $buff[] = "</script>";
        $buff[] = "<a href=\"" . DOKU_URL . ml($opts['id']) . "\" title=\"".$opts['id']."\">Download</a><br/>";

        $renderer->doc .= implode("\n", $buff);

        return true;
    }
}
