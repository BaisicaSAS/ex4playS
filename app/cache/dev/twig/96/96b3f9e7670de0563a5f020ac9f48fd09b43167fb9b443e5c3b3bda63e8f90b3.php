<?php

/* @LibreameBackend/2/mailer.html */
class __TwigTemplate_3e849d1aee429fd91867629aeb53f07e01a9d7b1cd7c9362eca8f5dd7e6b6b0d extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02 = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->enter($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof = new Twig_Profiler_Profile($this->getTemplateName(), "template", "@LibreameBackend/2/mailer.html"));

        // line 1
        echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>Confirmacion de registro en ex4read</title>
</head>
<body>

<div style=\"width:100%;\" align=\"center\">
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td align=\"center\" valign=\"top\" style=\"background-color:#53636e;\" bgcolor=\"#53636e;\">
    
    <br>
    <br>
    <table width=\"583\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
        <td align=\"left\" valign=\"top\" bgcolor=\"#FFFFFF\" style=\"background-color:#FFFFFF;\"><img src=\"images/header.jpg\" width=\"583\" height=\"118\"></td>
      </tr>
      <tr>
        <td align=\"left\" valign=\"top\" bgcolor=\"#FFFFFF\" style=\"background-color:#FFFFFF;\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td width=\"35\" align=\"left\" valign=\"top\">&nbsp;</td>
            <td align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td align=\"center\" valign=\"top\">
                \t<div>
\t\t\t\t\t\t<img src=\"C:\\BackupManuel\\Personal\\2. ArchivosTrabajo\\Baisica\\ex4Read\\logo_files\\400dpiLogo.png\" alt=\"ex4read\" width='135' height='134'/>
\t\t\t\t\t</div>
                \t<div style=\"color:#EF3340; font-family:Times New Roman, Times, serif; font-size:48px;\">
\t\t\t\t\t\tConfirmación de registro en ex4read
\t\t\t\t\t</div>
                  <div style=\"font-family: Verdana, Geneva, sans-serif; color:#898989; font-size:12px;\">Cambia tu lectura!.</div></td>
              </tr>
              <tr>
                <td align=\"left\" valign=\"top\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#525252;\">
                
                <div style=\"color:#00B08B; font-size:19px;\">Hola ";
        // line 37
        echo twig_escape_filter($this->env, (isset($context["usuario"]) || array_key_exists("usuario", $context) ? $context["usuario"] : (function () { throw new Twig_Error_Runtime('Variable "usuario" does not exist.', 37, $this->source); })()), "html", null, true);
        echo "</div>
                <div style=\"color:#00B08B; font-size:17px;\">
\t\t\t\t\t<br>Estamos casi listos para inciar en ex4read, solo confirma tu registro en la plataforma para finalizar.<br>
\t\t\t\t\t<br><li><a href=\"";
        // line 40
        echo twig_escape_filter($this->env, (isset($context["crurl"]) || array_key_exists("crurl", $context) ? $context["crurl"] : (function () { throw new Twig_Error_Runtime('Variable "crurl" does not exist.', 40, $this->source); })()), "html", null, true);
        echo "\">Confirmar mi registro.</a></li><br>
\t\t\t\t</div>
<br>
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"13%\"><b><img src=\"images/tweet.gif\" alt=\"\" width=\"24\" height=\"23\"> <img src=\"images/facebook.gif\" alt=\"\" width=\"24\" height=\"23\"></b></td>
    <td width=\"87%\" style=\"font-size:11px; color:#525252; font-family:Arial, Helvetica, sans-serif;\"><b>Servicio al cliente: <a href='servicio@ex4read.co'>servicio@ex4read.co</a></b></td>
  </tr>
</table></td>
              </tr>
              <tr>
                <td align=\"left\" valign=\"top\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#525252;\">&nbsp;</td>
              </tr>
            </table></td>
            <td width=\"35\" align=\"left\" valign=\"top\">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align=\"left\" valign=\"top\" bgcolor=\"#3d90bd\" style=\"background-color:#3d90bd;\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td width=\"35\">&nbsp;</td>
            <td height=\"50\" valign=\"middle\" style=\"color:#FFFFFF; font-size:11px; font-family:Arial, Helvetica, sans-serif;\"><b> </b><br> </td>
            <td width=\"35\">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
  </table>
    <br>
    <br></td>
  </tr>
</table>

</div>

</body>
</html>
";
        
        $__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02->leave($__internal_319393461309892924ff6e74d6d6e64287df64b63545b994e100d4ab223aed02_prof);

    }

    public function getTemplateName()
    {
        return "@LibreameBackend/2/mailer.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 40,  64 => 37,  26 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>Confirmacion de registro en ex4read</title>
</head>
<body>

<div style=\"width:100%;\" align=\"center\">
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td align=\"center\" valign=\"top\" style=\"background-color:#53636e;\" bgcolor=\"#53636e;\">
    
    <br>
    <br>
    <table width=\"583\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
        <td align=\"left\" valign=\"top\" bgcolor=\"#FFFFFF\" style=\"background-color:#FFFFFF;\"><img src=\"images/header.jpg\" width=\"583\" height=\"118\"></td>
      </tr>
      <tr>
        <td align=\"left\" valign=\"top\" bgcolor=\"#FFFFFF\" style=\"background-color:#FFFFFF;\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td width=\"35\" align=\"left\" valign=\"top\">&nbsp;</td>
            <td align=\"left\" valign=\"top\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
              <tr>
                <td align=\"center\" valign=\"top\">
                \t<div>
\t\t\t\t\t\t<img src=\"C:\\BackupManuel\\Personal\\2. ArchivosTrabajo\\Baisica\\ex4Read\\logo_files\\400dpiLogo.png\" alt=\"ex4read\" width='135' height='134'/>
\t\t\t\t\t</div>
                \t<div style=\"color:#EF3340; font-family:Times New Roman, Times, serif; font-size:48px;\">
\t\t\t\t\t\tConfirmación de registro en ex4read
\t\t\t\t\t</div>
                  <div style=\"font-family: Verdana, Geneva, sans-serif; color:#898989; font-size:12px;\">Cambia tu lectura!.</div></td>
              </tr>
              <tr>
                <td align=\"left\" valign=\"top\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#525252;\">
                
                <div style=\"color:#00B08B; font-size:19px;\">Hola {{ usuario }}</div>
                <div style=\"color:#00B08B; font-size:17px;\">
\t\t\t\t\t<br>Estamos casi listos para inciar en ex4read, solo confirma tu registro en la plataforma para finalizar.<br>
\t\t\t\t\t<br><li><a href=\"{{ crurl }}\">Confirmar mi registro.</a></li><br>
\t\t\t\t</div>
<br>
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"13%\"><b><img src=\"images/tweet.gif\" alt=\"\" width=\"24\" height=\"23\"> <img src=\"images/facebook.gif\" alt=\"\" width=\"24\" height=\"23\"></b></td>
    <td width=\"87%\" style=\"font-size:11px; color:#525252; font-family:Arial, Helvetica, sans-serif;\"><b>Servicio al cliente: <a href='servicio@ex4read.co'>servicio@ex4read.co</a></b></td>
  </tr>
</table></td>
              </tr>
              <tr>
                <td align=\"left\" valign=\"top\" style=\"font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#525252;\">&nbsp;</td>
              </tr>
            </table></td>
            <td width=\"35\" align=\"left\" valign=\"top\">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align=\"left\" valign=\"top\" bgcolor=\"#3d90bd\" style=\"background-color:#3d90bd;\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
          <tr>
            <td width=\"35\">&nbsp;</td>
            <td height=\"50\" valign=\"middle\" style=\"color:#FFFFFF; font-size:11px; font-family:Arial, Helvetica, sans-serif;\"><b> </b><br> </td>
            <td width=\"35\">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
  </table>
    <br>
    <br></td>
  </tr>
</table>

</div>

</body>
</html>
", "@LibreameBackend/2/mailer.html", "C:\\xampp\\htdocs\\ex4playS\\src\\Libreame\\BackendBundle\\Resources\\views\\2\\mailer.html");
    }
}
