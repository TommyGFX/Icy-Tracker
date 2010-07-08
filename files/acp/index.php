<?php
/**
 * @author		Markus Bartz
 * @copyright	%COPYRIGHT%
 * @license		%LICENSE%
 * @version		$Id$
 */
require_once('./global.php');
RequestHandler::handle(ArrayUtil::appendSuffix($packageDirs, 'lib/acp/'));
?>