<?php

/* SensioDistributionBundle:Configurator/Step:secret.html.twig */
class __TwigTemplate_bee208bb99cb3d47b46472a2ec97294c36825ef9b3d2ae7a3626702e38322f6b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("SensioDistributionBundle::Configurator/layout.html.twig");

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "SensioDistributionBundle::Configurator/layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Symfony - Configure global Secret";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        $this->env->getExtension('form')->renderer->setTheme((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), array(0 => "SensioDistributionBundle::Configurator/form.html.twig"));
        // line 7
        echo "
    <div class=\"step\">
        ";
        // line 9
        $this->env->loadTemplate("SensioDistributionBundle::Configurator/steps.html.twig")->display(array_merge($context, array("index" => (isset($context["index"]) ? $context["index"] : $this->getContext($context, "index")), "count" => (isset($context["count"]) ? $context["count"] : $this->getContext($context, "count")))));
        // line 10
        echo "
        <h1>Global Secret</h1>
        <p>Configure the global secret for your website (the secret is used for the CSRF protection among other things):</p>

        <div class=\"symfony-form-errors\">
            ";
        // line 15
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'errors');
        echo "
        </div>
        <form action=\"";
        // line 17
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("_configurator_step", array("index" => (isset($context["index"]) ? $context["index"] : $this->getContext($context, "index")))), "html", null, true);
        echo " \" method=\"POST\">
            <div class=\"symfony-form-row\">
                ";
        // line 19
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "secret"), 'label');
        echo "
                <div class=\"symfony-form-field\">
                    ";
        // line 21
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "secret"), 'widget');
        echo "
                    <a href=\"#\" onclick=\"generateSecret(); return false;\" class=\"sf-button\">
                        <span class=\"border-l\">
                            <span class=\"border-r\">
                                <span class=\"btn-bg\">Generate</span>
                            </span>
                        </span>
                    </a>
                    <div class=\"symfony-form-errors\">
                        ";
        // line 30
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getAttribute((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), "secret"), 'errors');
        echo "
                    </div>
                </div>
            </div>

            ";
        // line 35
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'rest');
        echo "

            <div class=\"symfony-form-footer\">
                <p>
                    <button type=\"submit\" class=\"sf-button\">
                        <span class=\"border-l\">
                            <span class=\"border-r\">
                                <span class=\"btn-bg\">NEXT STEP</span>
                            </span>
                        </span>
                    </button>
                </p>
                <p>* mandatory fields</p>
            </div>

        </form>

        <script type=\"text/javascript\">
            function generateSecret()
            {
                var result = '';
                for (i=0; i < 32; i++) {
                    result += Math.round(Math.random()*16).toString(16);
                }
                document.getElementById('distributionbundle_secret_step_secret').value = result;
            }
        </script>
    </div>
";
    }

    public function getTemplateName()
    {
        return "SensioDistributionBundle:Configurator/Step:secret.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1092 => 342,  1086 => 340,  1080 => 338,  1078 => 337,  1076 => 336,  1072 => 335,  1063 => 334,  1060 => 333,  1048 => 328,  1042 => 326,  1036 => 324,  1034 => 323,  1032 => 322,  1028 => 321,  1022 => 320,  1019 => 319,  1007 => 314,  1001 => 312,  995 => 310,  993 => 309,  991 => 308,  987 => 307,  981 => 306,  975 => 305,  971 => 304,  967 => 303,  963 => 302,  957 => 301,  954 => 300,  946 => 296,  942 => 295,  939 => 294,  930 => 287,  928 => 286,  924 => 285,  921 => 284,  916 => 280,  908 => 278,  904 => 277,  902 => 276,  900 => 275,  897 => 274,  891 => 271,  888 => 270,  884 => 267,  881 => 265,  879 => 264,  876 => 263,  869 => 259,  867 => 258,  843 => 257,  840 => 255,  837 => 253,  835 => 252,  833 => 251,  830 => 250,  826 => 247,  824 => 246,  822 => 245,  819 => 244,  815 => 239,  812 => 238,  808 => 235,  804 => 233,  801 => 232,  797 => 229,  795 => 228,  793 => 227,  791 => 226,  789 => 225,  786 => 224,  782 => 221,  779 => 216,  774 => 212,  754 => 208,  751 => 206,  748 => 205,  739 => 200,  737 => 199,  735 => 198,  732 => 197,  728 => 192,  726 => 191,  719 => 187,  717 => 186,  714 => 185,  704 => 182,  701 => 180,  699 => 179,  696 => 178,  692 => 175,  687 => 173,  683 => 170,  681 => 169,  671 => 164,  663 => 160,  661 => 159,  658 => 158,  654 => 155,  652 => 154,  649 => 153,  643 => 149,  636 => 145,  633 => 144,  627 => 140,  624 => 139,  620 => 136,  617 => 135,  614 => 133,  599 => 128,  594 => 127,  592 => 126,  589 => 124,  587 => 123,  584 => 122,  579 => 118,  576 => 115,  575 => 114,  574 => 113,  570 => 112,  567 => 110,  565 => 109,  562 => 108,  556 => 104,  554 => 103,  550 => 101,  544 => 99,  539 => 96,  536 => 95,  502 => 87,  477 => 82,  472 => 79,  470 => 78,  443 => 74,  425 => 64,  421 => 62,  399 => 56,  377 => 47,  370 => 45,  349 => 34,  346 => 33,  339 => 28,  330 => 23,  287 => 5,  282 => 3,  260 => 293,  257 => 291,  250 => 274,  245 => 270,  242 => 269,  237 => 262,  146 => 147,  126 => 121,  124 => 108,  114 => 91,  356 => 330,  354 => 329,  20 => 1,  195 => 92,  186 => 190,  137 => 57,  129 => 122,  806 => 234,  803 => 487,  792 => 485,  788 => 484,  784 => 482,  771 => 481,  745 => 203,  742 => 202,  723 => 190,  706 => 471,  702 => 469,  698 => 468,  694 => 467,  690 => 174,  686 => 465,  682 => 464,  678 => 168,  675 => 462,  673 => 165,  656 => 460,  645 => 150,  630 => 452,  625 => 450,  621 => 449,  618 => 448,  616 => 447,  597 => 442,  528 => 406,  523 => 404,  518 => 402,  513 => 400,  389 => 51,  386 => 159,  378 => 157,  371 => 156,  363 => 153,  358 => 151,  343 => 146,  340 => 145,  331 => 140,  328 => 22,  326 => 21,  307 => 287,  302 => 125,  296 => 121,  293 => 120,  290 => 7,  281 => 114,  276 => 111,  259 => 103,  253 => 100,  232 => 249,  222 => 238,  210 => 77,  155 => 47,  152 => 46,  357 => 37,  345 => 147,  334 => 26,  332 => 116,  327 => 114,  324 => 113,  321 => 18,  318 => 111,  306 => 107,  291 => 102,  274 => 110,  265 => 299,  263 => 294,  255 => 284,  231 => 83,  212 => 224,  202 => 94,  190 => 76,  174 => 65,  104 => 74,  672 => 345,  668 => 163,  664 => 342,  660 => 340,  651 => 337,  647 => 336,  644 => 335,  640 => 148,  631 => 327,  629 => 141,  626 => 325,  622 => 323,  613 => 320,  609 => 129,  606 => 318,  602 => 445,  593 => 310,  591 => 309,  588 => 308,  585 => 307,  581 => 305,  577 => 116,  569 => 300,  563 => 410,  559 => 296,  557 => 295,  552 => 102,  548 => 100,  545 => 407,  541 => 97,  533 => 284,  531 => 283,  527 => 281,  525 => 405,  522 => 92,  519 => 91,  515 => 276,  509 => 272,  505 => 88,  499 => 268,  497 => 267,  489 => 262,  483 => 258,  479 => 256,  473 => 254,  471 => 253,  465 => 77,  463 => 76,  459 => 246,  454 => 244,  448 => 240,  438 => 236,  436 => 235,  428 => 230,  418 => 224,  412 => 60,  410 => 59,  400 => 214,  397 => 55,  383 => 49,  376 => 205,  367 => 155,  353 => 149,  347 => 191,  317 => 17,  313 => 183,  304 => 181,  297 => 104,  295 => 11,  288 => 118,  205 => 108,  188 => 194,  184 => 63,  170 => 84,  175 => 86,  161 => 162,  118 => 49,  100 => 36,  462 => 202,  449 => 198,  446 => 75,  441 => 196,  439 => 71,  431 => 189,  429 => 66,  422 => 226,  415 => 180,  408 => 176,  401 => 172,  394 => 54,  380 => 158,  373 => 46,  361 => 152,  351 => 328,  348 => 121,  342 => 30,  338 => 119,  335 => 134,  329 => 188,  325 => 129,  323 => 19,  320 => 127,  315 => 131,  303 => 106,  300 => 13,  289 => 113,  286 => 112,  275 => 332,  270 => 318,  267 => 101,  262 => 98,  256 => 96,  248 => 97,  233 => 87,  226 => 84,  216 => 79,  213 => 78,  207 => 216,  200 => 72,  197 => 69,  194 => 197,  191 => 196,  185 => 74,  181 => 185,  178 => 184,  172 => 57,  165 => 83,  153 => 67,  150 => 55,  134 => 133,  113 => 40,  81 => 30,  70 => 19,  65 => 17,  34 => 4,  97 => 41,  58 => 15,  127 => 35,  110 => 38,  90 => 27,  84 => 33,  77 => 25,  76 => 25,  53 => 11,  23 => 1,  480 => 162,  474 => 80,  469 => 158,  461 => 155,  457 => 245,  453 => 199,  444 => 238,  440 => 148,  437 => 70,  435 => 69,  430 => 144,  427 => 65,  423 => 63,  413 => 134,  409 => 132,  407 => 131,  402 => 58,  398 => 129,  393 => 211,  387 => 164,  384 => 121,  381 => 48,  379 => 119,  374 => 116,  368 => 340,  365 => 41,  362 => 39,  360 => 38,  355 => 150,  341 => 189,  337 => 27,  322 => 101,  314 => 16,  312 => 130,  309 => 288,  305 => 95,  298 => 12,  294 => 90,  285 => 4,  283 => 115,  278 => 333,  268 => 300,  264 => 84,  258 => 94,  252 => 283,  247 => 273,  241 => 93,  229 => 87,  220 => 81,  214 => 231,  177 => 65,  169 => 168,  140 => 58,  132 => 51,  128 => 49,  107 => 37,  61 => 2,  273 => 319,  269 => 107,  254 => 92,  243 => 92,  240 => 263,  238 => 85,  235 => 250,  230 => 244,  227 => 243,  224 => 241,  221 => 77,  219 => 237,  217 => 232,  208 => 76,  204 => 215,  179 => 87,  159 => 158,  143 => 51,  135 => 62,  119 => 95,  102 => 30,  71 => 15,  67 => 16,  63 => 21,  59 => 17,  38 => 6,  94 => 45,  89 => 35,  85 => 26,  75 => 22,  68 => 20,  56 => 12,  87 => 26,  21 => 2,  26 => 3,  93 => 28,  88 => 28,  78 => 24,  46 => 14,  27 => 3,  44 => 8,  31 => 3,  28 => 3,  201 => 213,  196 => 211,  183 => 189,  171 => 173,  166 => 167,  163 => 82,  158 => 80,  156 => 157,  151 => 152,  142 => 59,  138 => 54,  136 => 138,  121 => 107,  117 => 39,  105 => 25,  91 => 44,  62 => 14,  49 => 12,  24 => 2,  25 => 35,  19 => 1,  79 => 26,  72 => 21,  69 => 21,  47 => 10,  40 => 11,  37 => 7,  22 => 2,  246 => 136,  157 => 89,  145 => 62,  139 => 139,  131 => 132,  123 => 48,  120 => 31,  115 => 40,  111 => 90,  108 => 33,  101 => 73,  98 => 29,  96 => 53,  83 => 30,  74 => 16,  66 => 10,  55 => 12,  52 => 12,  50 => 10,  43 => 12,  41 => 7,  35 => 5,  32 => 6,  29 => 3,  209 => 223,  203 => 98,  199 => 212,  193 => 73,  189 => 66,  187 => 75,  182 => 88,  176 => 178,  173 => 177,  168 => 61,  164 => 163,  162 => 59,  154 => 153,  149 => 148,  147 => 75,  144 => 144,  141 => 143,  133 => 55,  130 => 46,  125 => 42,  122 => 41,  116 => 94,  112 => 39,  109 => 87,  106 => 86,  103 => 32,  99 => 54,  95 => 39,  92 => 31,  86 => 36,  82 => 25,  80 => 24,  73 => 23,  64 => 19,  60 => 20,  57 => 19,  54 => 15,  51 => 37,  48 => 10,  45 => 9,  42 => 7,  39 => 10,  36 => 5,  33 => 4,  30 => 3,);
    }
}
