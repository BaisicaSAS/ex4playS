<?php

/* WebProfilerBundle:Collector:logger.html.twig */
class __TwigTemplate_f67b60867bf47881e259dab4630cd2e103ff968dcde508c4a16d776dd7038f0d extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("@WebProfiler/Profiler/layout.html.twig", "WebProfilerBundle:Collector:logger.html.twig", 1);
        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
            'menu' => array($this, 'block_menu'),
            'panel' => array($this, 'block_panel'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@WebProfiler/Profiler/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "WebProfilerBundle:Collector:logger.html.twig"));

        // line 3
        $context["helper"] = $this;
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 5
    public function block_toolbar($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "toolbar"));

        // line 6
        echo "    ";
        if (((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 6, $this->source); })()), "counterrors", array()) || twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 6, $this->source); })()), "countdeprecations", array())) || twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 6, $this->source); })()), "countscreams", array()))) {
            // line 7
            echo "        ";
            ob_start();
            // line 8
            echo "            ";
            $context["status_color"] = ((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 8, $this->source); })()), "counterrors", array())) ? ("red") : (((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 8, $this->source); })()), "countdeprecations", array())) ? ("yellow") : (""))));
            // line 9
            echo "            ";
            $context["error_count"] = ((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 9, $this->source); })()), "counterrors", array()) + twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 9, $this->source); })()), "countdeprecations", array())) + twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 9, $this->source); })()), "countscreams", array()));
            // line 10
            echo "            ";
            echo twig_include($this->env, $context, "@WebProfiler/Icon/logger.svg");
            echo "
            <span class=\"sf-toolbar-value\">";
            // line 11
            echo twig_escape_filter($this->env, (isset($context["error_count"]) || array_key_exists("error_count", $context) ? $context["error_count"] : (function () { throw new Twig_Error_Runtime('Variable "error_count" does not exist.', 11, $this->source); })()), "html", null, true);
            echo "</span>
        ";
            $context["icon"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 13
            echo "
        ";
            // line 14
            ob_start();
            // line 15
            echo "            <div class=\"sf-toolbar-info-piece\">
                <b>Errors</b>
                <span class=\"sf-toolbar-status sf-toolbar-status-";
            // line 17
            echo ((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 17, $this->source); })()), "counterrors", array())) ? ("red") : (""));
            echo "\">";
            echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "counterrors", array(), "any", true, true)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "counterrors", array()), 0)) : (0)), "html", null, true);
            echo "</span>
            </div>

            <div class=\"sf-toolbar-info-piece\">
                <b>Deprecated Calls</b>
                <span class=\"sf-toolbar-status sf-toolbar-status-";
            // line 22
            echo ((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 22, $this->source); })()), "countdeprecations", array())) ? ("yellow") : (""));
            echo "\">";
            echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "countdeprecations", array(), "any", true, true)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "countdeprecations", array()), 0)) : (0)), "html", null, true);
            echo "</span>
            </div>

            <div class=\"sf-toolbar-info-piece\">
                <b>Silenced Errors</b>
                <span class=\"sf-toolbar-status\">";
            // line 27
            echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "countscreams", array(), "any", true, true)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "countscreams", array()), 0)) : (0)), "html", null, true);
            echo "</span>
            </div>
        ";
            $context["text"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 30
            echo "
        ";
            // line 31
            echo twig_include($this->env, $context, "@WebProfiler/Profiler/toolbar_item.html.twig", array("link" => (isset($context["profiler_url"]) || array_key_exists("profiler_url", $context) ? $context["profiler_url"] : (function () { throw new Twig_Error_Runtime('Variable "profiler_url" does not exist.', 31, $this->source); })()), "status" => (isset($context["status_color"]) || array_key_exists("status_color", $context) ? $context["status_color"] : (function () { throw new Twig_Error_Runtime('Variable "status_color" does not exist.', 31, $this->source); })())));
            echo "
    ";
        }
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 35
    public function block_menu($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "menu"));

        // line 36
        echo "    <span class=\"label label-status-";
        echo ((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 36, $this->source); })()), "counterrors", array())) ? ("error") : (((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 36, $this->source); })()), "countdeprecations", array())) ? ("warning") : (""))));
        echo " ";
        echo ((twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 36, $this->source); })()), "logs", array()))) ? ("disabled") : (""));
        echo "\">
        <span class=\"icon\">";
        // line 37
        echo twig_include($this->env, $context, "@WebProfiler/Icon/logger.svg");
        echo "</span>
        <strong>Logs</strong>
        ";
        // line 39
        if ((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 39, $this->source); })()), "counterrors", array()) || twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 39, $this->source); })()), "countdeprecations", array()))) {
            // line 40
            echo "            <span class=\"count\">
                <span>";
            // line 41
            echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 41, $this->source); })()), "counterrors", array())) ? (twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 41, $this->source); })()), "counterrors", array())) : (twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 41, $this->source); })()), "countdeprecations", array()))), "html", null, true);
            echo "</span>
            </span>
        ";
        }
        // line 44
        echo "    </span>
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 47
    public function block_panel($context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "block", "panel"));

        // line 48
        echo "    <h2>Log Messages</h2>

    ";
        // line 50
        if (twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 50, $this->source); })()), "logs", array()))) {
            // line 51
            echo "        <div class=\"empty\">
            <p>No log messages available.</p>
        </div>
    ";
        } else {
            // line 55
            echo "        ";
            // line 56
            echo "        ";
            list($context["deprecation_logs"], $context["debug_logs"], $context["info_and_error_logs"], $context["silenced_logs"]) =             array(array(), array(), array(), array());
            // line 57
            echo "        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, (isset($context["collector"]) || array_key_exists("collector", $context) ? $context["collector"] : (function () { throw new Twig_Error_Runtime('Variable "collector" does not exist.', 57, $this->source); })()), "logs", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
                // line 58
                echo "            ";
                if (((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["log"], "context", array(), "any", false, true), "level", array(), "any", true, true) && twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["log"], "context", array(), "any", false, true), "type", array(), "any", true, true)) && twig_in_filter(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["log"], "context", array()), "type", array()), array(0 => twig_constant("E_DEPRECATED"), 1 => twig_constant("E_USER_DEPRECATED"))))) {
                    // line 59
                    echo "                ";
                    $context["deprecation_logs"] = twig_array_merge((isset($context["deprecation_logs"]) || array_key_exists("deprecation_logs", $context) ? $context["deprecation_logs"] : (function () { throw new Twig_Error_Runtime('Variable "deprecation_logs" does not exist.', 59, $this->source); })()), array(0 => $context["log"]));
                    // line 60
                    echo "            ";
                } elseif ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["log"], "context", array(), "any", false, true), "scream", array(), "any", true, true) && (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, $context["log"], "context", array()), "scream", array()) == true))) {
                    // line 61
                    echo "                ";
                    $context["silenced_logs"] = twig_array_merge((isset($context["silenced_logs"]) || array_key_exists("silenced_logs", $context) ? $context["silenced_logs"] : (function () { throw new Twig_Error_Runtime('Variable "silenced_logs" does not exist.', 61, $this->source); })()), array(0 => $context["log"]));
                    // line 62
                    echo "            ";
                } elseif ((twig_get_attribute($this->env, $this->source, $context["log"], "priorityName", array()) == "DEBUG")) {
                    // line 63
                    echo "                ";
                    $context["debug_logs"] = twig_array_merge((isset($context["debug_logs"]) || array_key_exists("debug_logs", $context) ? $context["debug_logs"] : (function () { throw new Twig_Error_Runtime('Variable "debug_logs" does not exist.', 63, $this->source); })()), array(0 => $context["log"]));
                    // line 64
                    echo "            ";
                } else {
                    // line 65
                    echo "                ";
                    $context["info_and_error_logs"] = twig_array_merge((isset($context["info_and_error_logs"]) || array_key_exists("info_and_error_logs", $context) ? $context["info_and_error_logs"] : (function () { throw new Twig_Error_Runtime('Variable "info_and_error_logs" does not exist.', 65, $this->source); })()), array(0 => $context["log"]));
                    // line 66
                    echo "            ";
                }
                // line 67
                echo "        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 68
            echo "
        <div class=\"sf-tabs\">
            <div class=\"tab\">
                <h3 class=\"tab-title\">Info. &amp; Errors <span class=\"badge\">";
            // line 71
            echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["info_and_error_logs"]) || array_key_exists("info_and_error_logs", $context) ? $context["info_and_error_logs"] : (function () { throw new Twig_Error_Runtime('Variable "info_and_error_logs" does not exist.', 71, $this->source); })())), "html", null, true);
            echo "</span></h3>

                <div class=\"tab-content\">
                    ";
            // line 74
            if (twig_test_empty((isset($context["info_and_error_logs"]) || array_key_exists("info_and_error_logs", $context) ? $context["info_and_error_logs"] : (function () { throw new Twig_Error_Runtime('Variable "info_and_error_logs" does not exist.', 74, $this->source); })()))) {
                // line 75
                echo "                        <div class=\"empty\">
                            <p>There are no log messages of this level.</p>
                        </div>
                    ";
            } else {
                // line 79
                echo "                        ";
                echo $context["helper"]->macro_render_table((isset($context["info_and_error_logs"]) || array_key_exists("info_and_error_logs", $context) ? $context["info_and_error_logs"] : (function () { throw new Twig_Error_Runtime('Variable "info_and_error_logs" does not exist.', 79, $this->source); })()), true);
                echo "
                    ";
            }
            // line 81
            echo "                </div>
            </div>

            <div class=\"tab\">
                ";
            // line 87
            echo "                <h3 class=\"tab-title\">Deprecations <span class=\"badge\">";
            echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "countdeprecations", array(), "any", true, true)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "countdeprecations", array()), 0)) : (0)), "html", null, true);
            echo "</span></h3>

                <div class=\"tab-content\">
                    ";
            // line 90
            if (twig_test_empty((isset($context["deprecation_logs"]) || array_key_exists("deprecation_logs", $context) ? $context["deprecation_logs"] : (function () { throw new Twig_Error_Runtime('Variable "deprecation_logs" does not exist.', 90, $this->source); })()))) {
                // line 91
                echo "                        <div class=\"empty\">
                            <p>There are no log messages about deprecated features.</p>
                        </div>
                    ";
            } else {
                // line 95
                echo "                        ";
                echo $context["helper"]->macro_render_table((isset($context["deprecation_logs"]) || array_key_exists("deprecation_logs", $context) ? $context["deprecation_logs"] : (function () { throw new Twig_Error_Runtime('Variable "deprecation_logs" does not exist.', 95, $this->source); })()), false, true);
                echo "
                    ";
            }
            // line 97
            echo "                </div>
            </div>

            <div class=\"tab\">
                <h3 class=\"tab-title\">Debug <span class=\"badge\">";
            // line 101
            echo twig_escape_filter($this->env, twig_length_filter($this->env, (isset($context["debug_logs"]) || array_key_exists("debug_logs", $context) ? $context["debug_logs"] : (function () { throw new Twig_Error_Runtime('Variable "debug_logs" does not exist.', 101, $this->source); })())), "html", null, true);
            echo "</span></h3>

                <div class=\"tab-content\">
                    ";
            // line 104
            if (twig_test_empty((isset($context["debug_logs"]) || array_key_exists("debug_logs", $context) ? $context["debug_logs"] : (function () { throw new Twig_Error_Runtime('Variable "debug_logs" does not exist.', 104, $this->source); })()))) {
                // line 105
                echo "                        <div class=\"empty\">
                            <p>There are no log messages of this level.</p>
                        </div>
                    ";
            } else {
                // line 109
                echo "                        ";
                echo $context["helper"]->macro_render_table((isset($context["debug_logs"]) || array_key_exists("debug_logs", $context) ? $context["debug_logs"] : (function () { throw new Twig_Error_Runtime('Variable "debug_logs" does not exist.', 109, $this->source); })()));
                echo "
                    ";
            }
            // line 111
            echo "                </div>
            </div>

            <div class=\"tab\">
                <h3 class=\"tab-title\">Silenced Errors <span class=\"badge\">";
            // line 115
            echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "countscreams", array(), "any", true, true)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, ($context["collector"] ?? null), "countscreams", array()), 0)) : (0)), "html", null, true);
            echo "</span></h3>

                <div class=\"tab-content\">
                    ";
            // line 118
            if (twig_test_empty((isset($context["silenced_logs"]) || array_key_exists("silenced_logs", $context) ? $context["silenced_logs"] : (function () { throw new Twig_Error_Runtime('Variable "silenced_logs" does not exist.', 118, $this->source); })()))) {
                // line 119
                echo "                        <div class=\"empty\">
                            <p>There are no log messages of this level.</p>
                        </div>
                    ";
            } else {
                // line 123
                echo "                        ";
                echo $context["helper"]->macro_render_table((isset($context["silenced_logs"]) || array_key_exists("silenced_logs", $context) ? $context["silenced_logs"] : (function () { throw new Twig_Error_Runtime('Variable "silenced_logs" does not exist.', 123, $this->source); })()));
                echo "
                    ";
            }
            // line 125
            echo "                </div>
            </div>

        </div>
    ";
        }
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    // line 132
    public function macro_render_table($__logs__ = null, $__show_level__ = false, $__is_deprecation__ = false, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "logs" => $__logs__,
            "show_level" => $__show_level__,
            "is_deprecation" => $__is_deprecation__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "macro", "render_table"));

            // line 133
            echo "    ";
            $context["helper"] = $this;
            // line 134
            echo "    ";
            $context["channel_is_defined"] = twig_get_attribute($this->env, $this->source, twig_first($this->env, (isset($context["logs"]) || array_key_exists("logs", $context) ? $context["logs"] : (function () { throw new Twig_Error_Runtime('Variable "logs" does not exist.', 134, $this->source); })())), "channel", array(), "any", true, true);
            // line 135
            echo "
    <table class=\"logs\">
        <thead>
            <tr>
                <th>";
            // line 139
            echo (((isset($context["show_level"]) || array_key_exists("show_level", $context) ? $context["show_level"] : (function () { throw new Twig_Error_Runtime('Variable "show_level" does not exist.', 139, $this->source); })())) ? ("Level") : ("Time"));
            echo "</th>
                ";
            // line 140
            if ((isset($context["channel_is_defined"]) || array_key_exists("channel_is_defined", $context) ? $context["channel_is_defined"] : (function () { throw new Twig_Error_Runtime('Variable "channel_is_defined" does not exist.', 140, $this->source); })())) {
                echo "<th>Channel</th>";
            }
            // line 141
            echo "                <th>Message</th>
            </tr>
        </thead>

        <tbody>
            ";
            // line 146
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["logs"]) || array_key_exists("logs", $context) ? $context["logs"] : (function () { throw new Twig_Error_Runtime('Variable "logs" does not exist.', 146, $this->source); })()));
            $context['loop'] = array(
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            );
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["log"]) {
                // line 147
                echo "                ";
                $context["css_class"] = (((isset($context["is_deprecation"]) || array_key_exists("is_deprecation", $context) ? $context["is_deprecation"] : (function () { throw new Twig_Error_Runtime('Variable "is_deprecation" does not exist.', 147, $this->source); })())) ? ("") : (((twig_in_filter(twig_get_attribute($this->env, $this->source,                 // line 148
$context["log"], "priorityName", array()), array(0 => "CRITICAL", 1 => "ERROR", 2 => "ALERT", 3 => "EMERGENCY"))) ? ("status-error") : (((twig_in_filter(twig_get_attribute($this->env, $this->source,                 // line 149
$context["log"], "priorityName", array()), array(0 => "NOTICE", 1 => "WARNING"))) ? ("status-warning") : (""))))));
                // line 151
                echo "                <tr class=\"";
                echo twig_escape_filter($this->env, (isset($context["css_class"]) || array_key_exists("css_class", $context) ? $context["css_class"] : (function () { throw new Twig_Error_Runtime('Variable "css_class" does not exist.', 151, $this->source); })()), "html", null, true);
                echo "\">
                    <td class=\"font-normal text-small\">
                        ";
                // line 153
                if ((isset($context["show_level"]) || array_key_exists("show_level", $context) ? $context["show_level"] : (function () { throw new Twig_Error_Runtime('Variable "show_level" does not exist.', 153, $this->source); })())) {
                    // line 154
                    echo "                            <span class=\"colored text-bold nowrap\">";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["log"], "priorityName", array()), "html", null, true);
                    echo "</span>
                        ";
                }
                // line 156
                echo "                        <span class=\"text-muted nowrap newline\">";
                echo twig_escape_filter($this->env, twig_date_format_filter($this->env, twig_get_attribute($this->env, $this->source, $context["log"], "timestamp", array()), "H:i:s"), "html", null, true);
                echo "</span>
                    </td>

                    ";
                // line 159
                if ((isset($context["channel_is_defined"]) || array_key_exists("channel_is_defined", $context) ? $context["channel_is_defined"] : (function () { throw new Twig_Error_Runtime('Variable "channel_is_defined" does not exist.', 159, $this->source); })())) {
                    // line 160
                    echo "                        <td class=\"font-normal text-small text-bold nowrap\">";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["log"], "channel", array()), "html", null, true);
                    echo "</td>
                    ";
                }
                // line 162
                echo "
                    <td class=\"font-normal\">";
                // line 163
                echo $context["helper"]->macro_render_log_message(twig_get_attribute($this->env, $this->source, $context["loop"], "index", array()), $context["log"], (isset($context["is_deprecation"]) || array_key_exists("is_deprecation", $context) ? $context["is_deprecation"] : (function () { throw new Twig_Error_Runtime('Variable "is_deprecation" does not exist.', 163, $this->source); })()));
                echo "</td>
                </tr>
            ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['log'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 166
            echo "        </tbody>
    </table>
";
            
            $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    // line 170
    public function macro_render_log_message($__log_index__ = null, $__log__ = null, $__is_deprecation__ = false, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "log_index" => $__log_index__,
            "log" => $__log__,
            "is_deprecation" => $__is_deprecation__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
            $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "macro", "render_log_message"));

            // line 171
            echo "    ";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, (isset($context["log"]) || array_key_exists("log", $context) ? $context["log"] : (function () { throw new Twig_Error_Runtime('Variable "log" does not exist.', 171, $this->source); })()), "message", array()), "html", null, true);
            echo "

    ";
            // line 173
            if ((isset($context["is_deprecation"]) || array_key_exists("is_deprecation", $context) ? $context["is_deprecation"] : (function () { throw new Twig_Error_Runtime('Variable "is_deprecation" does not exist.', 173, $this->source); })())) {
                // line 174
                echo "        ";
                $context["stack"] = ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["log"] ?? null), "context", array(), "any", false, true), "stack", array(), "any", true, true)) ? (_twig_default_filter(twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["log"] ?? null), "context", array(), "any", false, true), "stack", array()), array())) : (array()));
                // line 175
                echo "        ";
                $context["id"] = ("sf-call-stack-" . (isset($context["log_index"]) || array_key_exists("log_index", $context) ? $context["log_index"] : (function () { throw new Twig_Error_Runtime('Variable "log_index" does not exist.', 175, $this->source); })()));
                // line 176
                echo "
        ";
                // line 177
                if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["log"] ?? null), "context", array(), "any", false, true), "errorCount", array(), "any", true, true)) {
                    // line 178
                    echo "            <span class=\"text-small text-bold\">(";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, (isset($context["log"]) || array_key_exists("log", $context) ? $context["log"] : (function () { throw new Twig_Error_Runtime('Variable "log" does not exist.', 178, $this->source); })()), "context", array()), "errorCount", array()), "html", null, true);
                    echo " times)</span>
        ";
                }
                // line 180
                echo "
        ";
                // line 181
                if ((isset($context["stack"]) || array_key_exists("stack", $context) ? $context["stack"] : (function () { throw new Twig_Error_Runtime('Variable "stack" does not exist.', 181, $this->source); })())) {
                    // line 182
                    echo "            <button class=\"btn-link text-small sf-toggle\" data-toggle-selector=\"#";
                    echo twig_escape_filter($this->env, (isset($context["id"]) || array_key_exists("id", $context) ? $context["id"] : (function () { throw new Twig_Error_Runtime('Variable "id" does not exist.', 182, $this->source); })()), "html", null, true);
                    echo "\" data-toggle-alt-content=\"Hide stack trace\">Show stack trace</button>
        ";
                }
                // line 184
                echo "
        ";
                // line 185
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["stack"]) || array_key_exists("stack", $context) ? $context["stack"] : (function () { throw new Twig_Error_Runtime('Variable "stack" does not exist.', 185, $this->source); })()));
                foreach ($context['_seq'] as $context["index"] => $context["call"]) {
                    if (($context["index"] > 1)) {
                        // line 186
                        echo "            ";
                        if (($context["index"] == 2)) {
                            // line 187
                            echo "                <ul class=\"sf-call-stack hidden\" id=\"";
                            echo twig_escape_filter($this->env, (isset($context["id"]) || array_key_exists("id", $context) ? $context["id"] : (function () { throw new Twig_Error_Runtime('Variable "id" does not exist.', 187, $this->source); })()), "html", null, true);
                            echo "\">
            ";
                        }
                        // line 189
                        echo "
            ";
                        // line 190
                        if (twig_get_attribute($this->env, $this->source, $context["call"], "class", array(), "any", true, true)) {
                            // line 191
                            echo "                ";
                            $context["from"] = (($this->extensions['Symfony\Bridge\Twig\Extension\CodeExtension']->abbrClass(twig_get_attribute($this->env, $this->source, $context["call"], "class", array())) . "::") . $this->extensions['Symfony\Bridge\Twig\Extension\CodeExtension']->abbrMethod(twig_get_attribute($this->env, $this->source, $context["call"], "function", array())));
                            // line 192
                            echo "            ";
                        } elseif (twig_get_attribute($this->env, $this->source, $context["call"], "function", array(), "any", true, true)) {
                            // line 193
                            echo "                ";
                            $context["from"] = $this->extensions['Symfony\Bridge\Twig\Extension\CodeExtension']->abbrMethod(twig_get_attribute($this->env, $this->source, $context["call"], "function", array()));
                            // line 194
                            echo "            ";
                        } elseif (twig_get_attribute($this->env, $this->source, $context["call"], "file", array(), "any", true, true)) {
                            // line 195
                            echo "                ";
                            $context["from"] = twig_get_attribute($this->env, $this->source, $context["call"], "file", array());
                            // line 196
                            echo "            ";
                        } else {
                            // line 197
                            echo "                ";
                            $context["from"] = "-";
                            // line 198
                            echo "            ";
                        }
                        // line 199
                        echo "
            ";
                        // line 200
                        $context["file_name"] = (((twig_get_attribute($this->env, $this->source, $context["call"], "file", array(), "any", true, true) && twig_get_attribute($this->env, $this->source, $context["call"], "line", array(), "any", true, true))) ? (twig_last($this->env, twig_split_filter($this->env, twig_replace_filter(twig_get_attribute($this->env, $this->source, $context["call"], "file", array()), array("\\" => "/")), "/"))) : (""));
                        // line 201
                        echo "
            <li>
                ";
                        // line 203
                        echo (isset($context["from"]) || array_key_exists("from", $context) ? $context["from"] : (function () { throw new Twig_Error_Runtime('Variable "from" does not exist.', 203, $this->source); })());
                        echo "
                ";
                        // line 204
                        if ((isset($context["file_name"]) || array_key_exists("file_name", $context) ? $context["file_name"] : (function () { throw new Twig_Error_Runtime('Variable "file_name" does not exist.', 204, $this->source); })())) {
                            // line 205
                            echo "                    <span class=\"text-small\">(called from ";
                            echo $this->extensions['Symfony\Bridge\Twig\Extension\CodeExtension']->formatFile(twig_get_attribute($this->env, $this->source, $context["call"], "file", array()), twig_get_attribute($this->env, $this->source, $context["call"], "line", array()), (isset($context["file_name"]) || array_key_exists("file_name", $context) ? $context["file_name"] : (function () { throw new Twig_Error_Runtime('Variable "file_name" does not exist.', 205, $this->source); })()));
                            echo ")</span>
                ";
                        }
                        // line 207
                        echo "            </li>

            ";
                        // line 209
                        if (($context["index"] == (twig_length_filter($this->env, (isset($context["stack"]) || array_key_exists("stack", $context) ? $context["stack"] : (function () { throw new Twig_Error_Runtime('Variable "stack" does not exist.', 209, $this->source); })())) - 1))) {
                            // line 210
                            echo "                </ul>
            ";
                        }
                        // line 212
                        echo "        ";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['index'], $context['call'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 213
                echo "    ";
            } else {
                // line 214
                echo "        ";
                if ((twig_get_attribute($this->env, $this->source, ($context["log"] ?? null), "context", array(), "any", true, true) &&  !twig_test_empty(twig_get_attribute($this->env, $this->source, (isset($context["log"]) || array_key_exists("log", $context) ? $context["log"] : (function () { throw new Twig_Error_Runtime('Variable "log" does not exist.', 214, $this->source); })()), "context", array())))) {
                    // line 215
                    echo "            <span class=\"metadata\">
                <strong>Context</strong>: ";
                    // line 216
                    echo twig_escape_filter($this->env, twig_replace_filter(json_encode(twig_get_attribute($this->env, $this->source, (isset($context["log"]) || array_key_exists("log", $context) ? $context["log"] : (function () { throw new Twig_Error_Runtime('Variable "log" does not exist.', 216, $this->source); })()), "context", array()), (64 | 256)), array("{\"" => "{ \"", "\"}" => "\" }", "\":{" => "\": {", "\":\"" => "\": \"", "\",\"" => "\", \"")), "html", null, true);
                    // line 218
                    echo "
            </span>
        ";
                }
                // line 221
                echo "    ";
            }
            
            $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);


            return ('' === $tmp = ob_get_contents()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:logger.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  615 => 221,  610 => 218,  608 => 216,  605 => 215,  602 => 214,  599 => 213,  592 => 212,  588 => 210,  586 => 209,  582 => 207,  576 => 205,  574 => 204,  570 => 203,  566 => 201,  564 => 200,  561 => 199,  558 => 198,  555 => 197,  552 => 196,  549 => 195,  546 => 194,  543 => 193,  540 => 192,  537 => 191,  535 => 190,  532 => 189,  526 => 187,  523 => 186,  518 => 185,  515 => 184,  509 => 182,  507 => 181,  504 => 180,  498 => 178,  496 => 177,  493 => 176,  490 => 175,  487 => 174,  485 => 173,  479 => 171,  462 => 170,  448 => 166,  431 => 163,  428 => 162,  422 => 160,  420 => 159,  413 => 156,  407 => 154,  405 => 153,  399 => 151,  397 => 149,  396 => 148,  394 => 147,  377 => 146,  370 => 141,  366 => 140,  362 => 139,  356 => 135,  353 => 134,  350 => 133,  333 => 132,  321 => 125,  315 => 123,  309 => 119,  307 => 118,  301 => 115,  295 => 111,  289 => 109,  283 => 105,  281 => 104,  275 => 101,  269 => 97,  263 => 95,  257 => 91,  255 => 90,  248 => 87,  242 => 81,  236 => 79,  230 => 75,  228 => 74,  222 => 71,  217 => 68,  211 => 67,  208 => 66,  205 => 65,  202 => 64,  199 => 63,  196 => 62,  193 => 61,  190 => 60,  187 => 59,  184 => 58,  179 => 57,  176 => 56,  174 => 55,  168 => 51,  166 => 50,  162 => 48,  156 => 47,  148 => 44,  142 => 41,  139 => 40,  137 => 39,  132 => 37,  125 => 36,  119 => 35,  109 => 31,  106 => 30,  100 => 27,  90 => 22,  80 => 17,  76 => 15,  74 => 14,  71 => 13,  66 => 11,  61 => 10,  58 => 9,  55 => 8,  52 => 7,  49 => 6,  43 => 5,  36 => 1,  34 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends '@WebProfiler/Profiler/layout.html.twig' %}

{% import _self as helper %}

{% block toolbar %}
    {% if collector.counterrors or collector.countdeprecations or collector.countscreams %}
        {% set icon %}
            {% set status_color = collector.counterrors ? 'red' : collector.countdeprecations ? 'yellow' : '' %}
            {% set error_count = collector.counterrors + collector.countdeprecations + collector.countscreams %}
            {{ include('@WebProfiler/Icon/logger.svg') }}
            <span class=\"sf-toolbar-value\">{{ error_count }}</span>
        {% endset %}

        {% set text %}
            <div class=\"sf-toolbar-info-piece\">
                <b>Errors</b>
                <span class=\"sf-toolbar-status sf-toolbar-status-{{ collector.counterrors ? 'red' }}\">{{ collector.counterrors|default(0) }}</span>
            </div>

            <div class=\"sf-toolbar-info-piece\">
                <b>Deprecated Calls</b>
                <span class=\"sf-toolbar-status sf-toolbar-status-{{ collector.countdeprecations ? 'yellow' }}\">{{ collector.countdeprecations|default(0) }}</span>
            </div>

            <div class=\"sf-toolbar-info-piece\">
                <b>Silenced Errors</b>
                <span class=\"sf-toolbar-status\">{{ collector.countscreams|default(0) }}</span>
            </div>
        {% endset %}

        {{ include('@WebProfiler/Profiler/toolbar_item.html.twig', { link: profiler_url, status: status_color }) }}
    {% endif %}
{% endblock %}

{% block menu %}
    <span class=\"label label-status-{{ collector.counterrors ? 'error' : collector.countdeprecations ? 'warning' }} {{ collector.logs is empty ? 'disabled' }}\">
        <span class=\"icon\">{{ include('@WebProfiler/Icon/logger.svg') }}</span>
        <strong>Logs</strong>
        {% if collector.counterrors or collector.countdeprecations %}
            <span class=\"count\">
                <span>{{ collector.counterrors ?: collector.countdeprecations }}</span>
            </span>
        {% endif %}
    </span>
{% endblock %}

{% block panel %}
    <h2>Log Messages</h2>

    {% if collector.logs is empty %}
        <div class=\"empty\">
            <p>No log messages available.</p>
        </div>
    {% else %}
        {# sort collected logs in groups #}
        {% set deprecation_logs, debug_logs, info_and_error_logs, silenced_logs = [], [], [], [] %}
        {% for log in collector.logs %}
            {% if log.context.level is defined and log.context.type is defined and log.context.type in [constant('E_DEPRECATED'), constant('E_USER_DEPRECATED')] %}
                {% set deprecation_logs = deprecation_logs|merge([log]) %}
            {% elseif log.context.scream is defined and log.context.scream == true  %}
                {% set silenced_logs = silenced_logs|merge([log]) %}
            {% elseif log.priorityName == 'DEBUG' %}
                {% set debug_logs = debug_logs|merge([log]) %}
            {% else %}
                {% set info_and_error_logs = info_and_error_logs|merge([log]) %}
            {% endif %}
        {% endfor %}

        <div class=\"sf-tabs\">
            <div class=\"tab\">
                <h3 class=\"tab-title\">Info. &amp; Errors <span class=\"badge\">{{ info_and_error_logs|length }}</span></h3>

                <div class=\"tab-content\">
                    {% if info_and_error_logs is empty %}
                        <div class=\"empty\">
                            <p>There are no log messages of this level.</p>
                        </div>
                    {% else %}
                        {{ helper.render_table(info_and_error_logs, true) }}
                    {% endif %}
                </div>
            </div>

            <div class=\"tab\">
                {# 'deprecation_logs|length' is not used because deprecations are
                now grouped and the group count doesn't match the message count #}
                <h3 class=\"tab-title\">Deprecations <span class=\"badge\">{{ collector.countdeprecations|default(0) }}</span></h3>

                <div class=\"tab-content\">
                    {% if deprecation_logs is empty %}
                        <div class=\"empty\">
                            <p>There are no log messages about deprecated features.</p>
                        </div>
                    {% else %}
                        {{ helper.render_table(deprecation_logs, false, true) }}
                    {% endif %}
                </div>
            </div>

            <div class=\"tab\">
                <h3 class=\"tab-title\">Debug <span class=\"badge\">{{ debug_logs|length }}</span></h3>

                <div class=\"tab-content\">
                    {% if debug_logs is empty %}
                        <div class=\"empty\">
                            <p>There are no log messages of this level.</p>
                        </div>
                    {% else %}
                        {{ helper.render_table(debug_logs) }}
                    {% endif %}
                </div>
            </div>

            <div class=\"tab\">
                <h3 class=\"tab-title\">Silenced Errors <span class=\"badge\">{{ collector.countscreams|default(0) }}</span></h3>

                <div class=\"tab-content\">
                    {% if silenced_logs is empty %}
                        <div class=\"empty\">
                            <p>There are no log messages of this level.</p>
                        </div>
                    {% else %}
                        {{ helper.render_table(silenced_logs) }}
                    {% endif %}
                </div>
            </div>

        </div>
    {% endif %}
{% endblock %}

{% macro render_table(logs, show_level = false, is_deprecation = false) %}
    {% import _self as helper %}
    {% set channel_is_defined = (logs|first).channel is defined %}

    <table class=\"logs\">
        <thead>
            <tr>
                <th>{{ show_level ? 'Level' : 'Time' }}</th>
                {% if channel_is_defined %}<th>Channel</th>{% endif %}
                <th>Message</th>
            </tr>
        </thead>

        <tbody>
            {% for log in logs %}
                {% set css_class = is_deprecation ? ''
                    : log.priorityName in ['CRITICAL', 'ERROR', 'ALERT', 'EMERGENCY'] ? 'status-error'
                    : log.priorityName in ['NOTICE', 'WARNING'] ? 'status-warning'
                %}
                <tr class=\"{{ css_class }}\">
                    <td class=\"font-normal text-small\">
                        {% if show_level %}
                            <span class=\"colored text-bold nowrap\">{{ log.priorityName }}</span>
                        {% endif %}
                        <span class=\"text-muted nowrap newline\">{{ log.timestamp|date('H:i:s') }}</span>
                    </td>

                    {% if channel_is_defined %}
                        <td class=\"font-normal text-small text-bold nowrap\">{{ log.channel }}</td>
                    {% endif %}

                    <td class=\"font-normal\">{{ helper.render_log_message(loop.index, log, is_deprecation) }}</td>
                </tr>
            {% endfor %}
        </tbody>
    </table>
{% endmacro %}

{% macro render_log_message(log_index, log, is_deprecation = false) %}
    {{ log.message }}

    {% if is_deprecation %}
        {% set stack = log.context.stack|default([]) %}
        {% set id = 'sf-call-stack-' ~ log_index %}

        {% if log.context.errorCount is defined %}
            <span class=\"text-small text-bold\">({{ log.context.errorCount }} times)</span>
        {% endif %}

        {% if stack %}
            <button class=\"btn-link text-small sf-toggle\" data-toggle-selector=\"#{{ id }}\" data-toggle-alt-content=\"Hide stack trace\">Show stack trace</button>
        {% endif %}

        {% for index, call in stack if index > 1 %}
            {% if index == 2 %}
                <ul class=\"sf-call-stack hidden\" id=\"{{ id }}\">
            {% endif %}

            {% if call.class is defined %}
                {% set from = call.class|abbr_class ~ '::' ~ call.function|abbr_method() %}
            {% elseif call.function is defined %}
                {% set from = call.function|abbr_method %}
            {% elseif call.file is defined %}
                {% set from = call.file %}
            {% else %}
                {% set from = '-' %}
            {% endif %}

            {% set file_name = (call.file is defined and call.line is defined) ? call.file|replace({'\\\\': '/'})|split('/')|last %}

            <li>
                {{ from|raw }}
                {% if file_name %}
                    <span class=\"text-small\">(called from {{ call.file|format_file(call.line, file_name)|raw }})</span>
                {% endif %}
            </li>

            {% if index == stack|length - 1 %}
                </ul>
            {% endif %}
        {% endfor %}
    {% else %}
        {% if log.context is defined and log.context is not empty %}
            <span class=\"metadata\">
                <strong>Context</strong>: {{ log.context|json_encode(64 b-or 256)|replace({
                    '{\"' : '{ \"', '\"}' : '\" }', '\":{' : '\": {', '\":\"' : '\": \"', '\",\"' : '\", \"'
                }) }}
            </span>
        {% endif %}
    {% endif %}
{% endmacro %}
", "WebProfilerBundle:Collector:logger.html.twig", "C:\\xampp\\htdocs\\ex4playS\\vendor\\symfony\\symfony\\src\\Symfony\\Bundle\\WebProfilerBundle/Resources/views/Collector/logger.html.twig");
    }
}
