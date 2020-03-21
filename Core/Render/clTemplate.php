<?php

namespace Core\Render;

/**
 * HTML-renderer and helper class for loading dependencies
 *
 * @author Dronki
 */
class clTemplate {

    private $aTopElements = array();
    private $aBottomElements = array();
    private $aPreContent = array();
    private $sContent;
    private $sTemplate;
    private $sPath;

    public function __construct($sPath = "", $sTemplate = 'default.php') {
        if (empty($sPath))
            $this->sPath = PATH_TEMPLATE;
        $this->sTemplate = $sTemplate;
    }

    public function addBottom($aParams = array()) {
        $aParams += array(
            'key' => null,
            'content' => null
        );

        $this->aBottomElements[$aParams['key']] = $aParams['content'];
    }

    public function addLink($aParams = array()) {
        $aParams += array(
            'key' => null,
            'href' => null,
            'rel' => 'stylesheet',
            'media' => null
        );
        $sKey = $aParams['key'];
        $aParams['id'] = $aParams['key'];
        unset($aParams['key']);

        $this->addTop(array(
            'key' => $sKey,
            'content' => (!empty($_GET['ajax']) ? '
				<script>
					if( $("#' . $aParams['id'] . '").length > 0 ) {
						$("#' . $aParams['id'] . '").attr({href : "' . $aParams['href'] . '"});
					} else {
						$("head").append(' . "'<link" . createAttributes($aParams) . ">'" . ');
					}
				</script>' : '<link' . createAttributes($aParams) . '>' )
        ));
    }

    public function addMeta($aParams = array()) {
        $aParams += array(
            'key' => null,
            'name' => null,
            'content' => null
        );
        $sKey = $aParams['key'];
        unset($aParams['key']);
        $aParams['content'] = htmlspecialchars(str_replace(array("\n", "\r", "\r\n"), '', $aParams['content']));
        if (!empty($_GET['ajax']))
            $aParams['content'] = htmlentities($aParams['content'], ENT_QUOTES);

        $this->addTop(array(
            'key' => $sKey,
            'content' => (!empty($_GET['ajax']) ? '
				<script>
					if( $("meta[name=' . "'" . $aParams['name'] . "'" . ']").length > 0 ) {
						$("meta[name=' . "'" . $aParams['name'] . "'" . ']").attr({content : "' . $aParams['content'] . '"});
					} else {
						$("head").append(' . "'<meta" . createAttributes($aParams) . ">'" . ');
					}
				</script>' : '<meta' . createAttributes($aParams) . '>' )
        ));
    }

    public function addScript($aParams = array()) {
        $aParams += array(
            'key' => null,
            'src' => null
        );
        $sKey = $aParams['key'];
        unset($aParams['key']);

        $this->addBottom(array(
            'key' => $sKey,
            'content' => '<script' . createAttributes($aParams) . '></script>'
        ));
    }

    public function addStyle($aParams = array()) {
        $aParams += array(
            'key' => null,
            'content' => null
        );

        $aParams['content'] = '<style>' . $aParams['content'] . '</style>';

        $this->addTop($aParams);
    }

    public function addTop($aParams = array()) {
        $aParams += array(
            'key' => null,
            'content' => null
        );

        $this->aTopElements[$aParams['key']] = $aParams['content'];
    }

    public function addPreContent($aParams = array()) {
        $aParams += array(
            'key' => null,
            'content' => null
        );
        $this->aPreContent[$aParams['key']] = $aParams['content'];
    }

    public function render() {
        $sTop = implode("\n", $this->aTopElements);
        $sBottom = implode("\n", $this->aBottomElements);
        $sPreContent = implode("\n", $this->aPreContent);
        $sContent = $this->sContent;

        ob_start();
        require $this->sPath . $this->sTemplate;
        $sTemplate = ob_get_clean();
        return $sTemplate;
    }

    public function setContent($sContent) {
        $this->sContent = $sContent;
    }

    public function setKeywords($sKeywords) {
        $this->addMeta(array(
            'key' => 'metaKeywords',
            'name' => 'keywords',
            'content' => $sKeywords
        ));
    }

    public function setDescription($sDescription) {
        $this->addMeta(array(
            'key' => 'metaDescription',
            'name' => 'description',
            'content' => htmlspecialchars($sDescription)
        ));
    }

    public function setCanonicalUrl($sCanonical) {
        $this->addTop(array(
            'key' => 'metaCanonicalUrl',
            'name' => 'canonical',
            'content' => '<link rel="canonical" href="' . htmlspecialchars($sCanonical, ENT_QUOTES) . '" />'
        ));
    }

    public function setTemplatePath($sPath) {
        $this->sPath = $sPath;
    }

    public function setTemplate($sTemplate) {
        if (file_exists($this->sPath . $sTemplate))
            $this->sTemplate = $sTemplate;
    }

    public function setBodyClass($aClasses = array()) {
        $sClasses = '';
        if (is_array($aClasses)) {
            $iCount = rand(1, 1000);
            foreach ($aClasses as $aClass) {
                $this->addBottom(array(
                    'key' => 'bodyClass' . $iCount,
                    'content' => '
						<script>
							document.body.classList.add("' . $aClass . '");
						</script>
					'
                ));
            }
        } else {
            $aClasses = explode(" ", $aClasses);
            $iCount = rand(1, 1000);
            foreach ($aClasses as $aClass) {
                $iCount++;
                $this->addBottom(array(
                    'key' => 'bodyClass' . $iCount,
                    'content' => '
						<script>
							document.body.classList.add("' . $aClass . '");
						</script>
					'
                ));
            }
        }
    }

    public function setWrapperClass($aClasses = array()) {
        $sClasses = '';
        if (is_array($aClasses)) {
            $iCount = rand(1, 1000);
            foreach ($aClasses as $aClass) {
                $this->addBottom(array(
                    'key' => 'wrapperClass' . $iCount,
                    'content' => '
						<script>
							document.bgetElementById("content-wrapper").classList.add("' . $aClass . '");
						</script>
					'
                ));
            }
        } else {
            $aClasses = explode(" ", $aClasses);
            $iCount = rand(1, 1000);
            foreach ($aClasses as $aClass) {
                $iCount++;
                $this->addBottom(array(
                    'key' => 'wrapperClass' . $iCount,
                    'content' => '
						<script>
							document.getElementById("content-wrapper").classList.add("' . $aClass . '");
						</script>
					'
                ));
            }
        }
    }

    public function setTitle($sTitle = null) {
        if ($sTitle === null)
            $sTitle = SITE_TITLE;
        $this->addTop(array(
            'key' => 'title',
            'content' => (!empty($_GET['ajax']) ? '
				<script>
					document.title = ' . "'" . htmlentities($sTitle, ENT_QUOTES) . "'" . ';
				</script>' : '<title>' . $sTitle . '</title>' )
        ));
    }

}
