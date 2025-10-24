<?php

/**
 * Dump and Die - Outputs variable contents and terminates execution.
 * The output is formatted with enhanced styling for better readability.
 *
 * @param mixed $var The variable to inspect
 * @param bool $showBacktrace Whether to display the call stack
 * @param bool $useVarDump Whether to use var_dump instead of print_r for all types
 * @return void
 */
function dd($var, bool $showBacktrace = false, bool $useVarDump = false): void
{
    echo '<style>
            .dd_container {
                background-color: #1a1a1a;
                color: #e0e0e0;
                padding: 20px;
                border: 2px solid #ff6b6b;
                border-radius: 8px;
                font-family: "Fira Code", "Consolas", "Courier New", monospace;
                font-size: 14px;
                line-height: 1.5;
                margin: 15px;
                white-space: pre-wrap;
                word-wrap: break-word;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
                z-index: 10000;
                position: relative;
                max-width: 95vw;
                overflow-x: auto;
            }
            .dd_header {
                color: #ff6b6b;
                font-weight: bold;
                font-size: 16px;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px solid #444;
            }
            .dd_type {
                color: #4fc3f7;
                font-weight: normal;
                font-size: 12px;
                margin-left: 10px;
                opacity: 0.8;
            }
            .dd_backtrace {
                background-color: #2a2a2a;
                padding: 15px;
                margin-top: 15px;
                border-radius: 4px;
                border-left: 4px solid #4fc3f7;
            }
            .dd_backtrace h4 {
                color: #4fc3f7;
                margin-top: 0;
                margin-bottom: 10px;
            }
            .string { color: #a5d6a7; }
            .integer { color: #4fc3f7; }
            .float { color: #4fc3f7; }
            .boolean { color: #ff9800; }
            .null { color: #ff5252; }
            .array { color: #e1bee7; }
            .object { color: #ffcc80; }
            .resource { color: #ff5252; }
          </style>';

    echo '<div class="dd_container">';
    
    $type = gettype($var);
    $typeDisplay = ucfirst($type);
    if ($type === 'object') {
        $typeDisplay .= ' (' . get_class($var) . ')';
    }
    
    echo '<div class="dd_header">DEBUG (dd) <span class="dd_type">' . $typeDisplay . '</span></div>';

    echo '<div class="dd_content">';
    
    if ($useVarDump) {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    } else {
        if (is_array($var) || is_object($var)) {
            echo "<pre>";
            print_r($var);
            echo "</pre>";
        } else {
            echo "<pre class='$type'>";
            
            switch ($type) {
                case 'string':
                    echo "string(" . strlen($var) . ") \"<span class='string'>$var</span>\"";
                    break;
                case 'integer':
                    echo "int(<span class='integer'>$var</span>)";
                    break;
                case 'double':
                    echo "float(<span class='float'>$var</span>)";
                    break;
                case 'boolean':
                    $boolStr = $var ? 'true' : 'false';
                    echo "bool(<span class='boolean'>$boolStr</span>)";
                    break;
                case 'NULL':
                    echo "<span class='null'>NULL</span>";
                    break;
                default:
                    var_dump($var);
                    break;
            }
            
            echo "</pre>";
        }
    }
    echo '</div>';

    if ($showBacktrace) {
        echo '<div class="dd_backtrace">';
        echo '<h4>Call Stack:</h4>';
        echo '<pre>';
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        array_shift($backtrace);
        
        foreach ($backtrace as $index => $call) {
            $file = $call['file'] ?? '[internal]';
            $line = $call['line'] ?? '?';
            $function = $call['function'] ?? '';
            $class = $call['class'] ?? '';
            $type = $call['type'] ?? '';
            
            echo "#$index $file($line): ";
            if ($class) {
                echo $class . $type;
            }
            echo $function . "()\n";
        }
        echo '</pre>';
        echo '</div>';
    }

    echo '</div>';
    die;
}

/**
 * Displays current memory usage and script execution time.
 * Useful for identifying performance bottlenecks.
 *
 * @return void
 */
function check_time_memory(): void
{
    $memory_usage = memory_get_usage(true);
    $time_elapsed = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

    echo '<style>
            .perf_container {
                background-color: #0E4B5A; /* Azul escuro */
                color: #00F0FF; /* Azul claro/ciano */
                padding: 8px;
                border: 1px solid #00F0FF;
                font-family: Consolas, "Courier New", monospace;
                font-size: 12px;
                position: fixed; /* Fixa na tela */
                bottom: 10px;
                right: 10px;
                z-index: 999999;
            }
          </style>';

    echo '<div class="perf_container">';
    echo 'MEMORY: ' . round($memory_usage / (1024 * 1024), 2) . ' MB | ';
    echo 'TIME: ' . round($time_elapsed, 4) . ' seconds';
    echo '</div>';
}
