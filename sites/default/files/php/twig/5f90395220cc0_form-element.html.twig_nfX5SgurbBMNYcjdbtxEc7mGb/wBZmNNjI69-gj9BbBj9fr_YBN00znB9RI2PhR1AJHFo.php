<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/thunder_admin/templates/form/form-element.html.twig */
class __TwigTemplate_1461e5553e4f36ce0a12824d7d49d00419fcd49034a059b737663de090b3bb66 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 49, "spaceless" => 70, "if" => 71, "include" => 95];
        $filters = ["escape" => 47, "clean_class" => 52];
        $functions = ["attach_library" => 47];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'spaceless', 'if', 'include'],
                ['escape', 'clean_class'],
                ['attach_library']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 47
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\Core\Template\TwigExtension')->attachLibrary("thunder_admin/form-toggle-description"), "html", null, true);
        echo "
";
        // line 49
        $context["classes"] = [0 => "js-form-item", 1 => "form-item", 2 => ("js-form-type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 52
($context["type"] ?? null)))), 3 => ("form-type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 53
($context["type"] ?? null)))), 4 => ("js-form-item-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 54
($context["name"] ?? null)))), 5 => ("form-item-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 55
($context["name"] ?? null)))), 6 => ((!twig_in_filter(        // line 56
($context["title_display"] ?? null), [0 => "after", 1 => "before"])) ? ("form-no-label") : ("")), 7 => (((        // line 57
($context["disabled"] ?? null) == "disabled")) ? ("form-disabled") : ("")), 8 => ((        // line 58
($context["errors"] ?? null)) ? ("form-item--error") : (""))];
        // line 62
        $context["description_classes"] = [0 => "description", 1 => "form-item__description", 2 => "js-form-item__description", 3 => "js-description", 4 => (((        // line 67
($context["description_display"] ?? null) == "invisible")) ? ("visually-hidden") : (""))];
        // line 70
        ob_start(function () { return ''; });
        // line 71
        echo "<div";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method")), "html", null, true);
        echo " ";
        if ($this->getAttribute(($context["description"] ?? null), "content", [])) {
            echo "data-form-description-container";
        }
        echo ">
  ";
        // line 72
        if (twig_in_filter(($context["label_display"] ?? null), [0 => "before", 1 => "invisible"])) {
            // line 73
            echo "    ";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null)), "html", null, true);
            echo "
  ";
        }
        // line 75
        echo "
  ";
        // line 76
        if (((($context["description_display"] ?? null) == "before") && $this->getAttribute(($context["description"] ?? null), "content", []))) {
            // line 77
            echo "    <div";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($this->getAttribute(($context["description"] ?? null), "attributes", []), "addClass", [0 => ($context["description_classes"] ?? null)], "method"), "addClass", [0 => "form-item__description--before"], "method")), "html", null, true);
            echo ">
      ";
            // line 78
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["description"] ?? null), "content", [])), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 81
        echo "  ";
        if ($this->getAttribute(($context["description"] ?? null), "content", [])) {
            // line 82
            echo "  <div class=\"form-item__field-wrapper--has-description\">
  ";
        }
        // line 84
        echo "    ";
        if ( !twig_test_empty(($context["prefix"] ?? null))) {
            // line 85
            echo "      <span class=\"field-prefix\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["prefix"] ?? null)), "html", null, true);
            echo "</span>
    ";
        }
        // line 87
        echo "    ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["children"] ?? null)), "html", null, true);
        echo "
    ";
        // line 88
        if ( !twig_test_empty(($context["suffix"] ?? null))) {
            // line 89
            echo "      <span class=\"field-suffix\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["suffix"] ?? null)), "html", null, true);
            echo "</span>
    ";
        }
        // line 91
        echo "    ";
        if ((($context["label_display"] ?? null) == "after")) {
            // line 92
            echo "      ";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null)), "html", null, true);
            echo "
    ";
        }
        // line 94
        echo "  ";
        if ($this->getAttribute(($context["description"] ?? null), "content", [])) {
            // line 95
            echo "      ";
            $this->loadTemplate("@thunder_admin/button-description-toggle.html.twig", "themes/thunder_admin/templates/form/form-element.html.twig", 95)->display(twig_array_merge($context, ["class" => "form-item__toggle-description"]));
            // line 96
            echo "    </div>
  ";
        }
        // line 98
        echo "

  ";
        // line 100
        if (($context["errors"] ?? null)) {
            // line 101
            echo "    <div class=\"form-item--error-message\">
      <strong>";
            // line 102
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null)), "html", null, true);
            echo "</strong>
    </div>
  ";
        }
        // line 105
        echo "  ";
        if ((twig_in_filter(($context["description_display"] ?? null), [0 => "after", 1 => "invisible"]) && $this->getAttribute(($context["description"] ?? null), "content", []))) {
            // line 106
            echo "    <div";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($this->getAttribute(($context["description"] ?? null), "attributes", []), "addClass", [0 => ($context["description_classes"] ?? null)], "method"), "addClass", [0 => "form-item__description--after"], "method")), "html", null, true);
            echo ">
      ";
            // line 107
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["description"] ?? null), "content", [])), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 110
        echo "</div>
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "themes/thunder_admin/templates/form/form-element.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  183 => 110,  177 => 107,  172 => 106,  169 => 105,  163 => 102,  160 => 101,  158 => 100,  154 => 98,  150 => 96,  147 => 95,  144 => 94,  138 => 92,  135 => 91,  129 => 89,  127 => 88,  122 => 87,  116 => 85,  113 => 84,  109 => 82,  106 => 81,  100 => 78,  95 => 77,  93 => 76,  90 => 75,  84 => 73,  82 => 72,  73 => 71,  71 => 70,  69 => 67,  68 => 62,  66 => 58,  65 => 57,  64 => 56,  63 => 55,  62 => 54,  61 => 53,  60 => 52,  59 => 49,  55 => 47,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "themes/thunder_admin/templates/form/form-element.html.twig", "C:\\wamp64\\www\\cbot2\\themes\\thunder_admin\\templates\\form\\form-element.html.twig");
    }
}
