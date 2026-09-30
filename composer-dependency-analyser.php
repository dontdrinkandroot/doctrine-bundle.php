<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return new Configuration()
    ->addPathToExclude(__DIR__ . '/src/Command/RenderOrmDiagramCommand.php');
