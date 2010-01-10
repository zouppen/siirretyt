<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns="http://www.w3.org/1999/xhtml"
		xmlns:php="http://iki.fi/zouppen/2010/php"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		exclude-result-prefixes="php"
		version="1.0">
		
  <xsl:output doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
	      method="xml" omit-xml-declaration="no" encoding="UTF-8" 
	      indent="yes"
	      doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
	      media-type="text/html"
	      />

  <xsl:variable name="otsikko">
    <xsl:choose>
      <xsl:when test="(/php:error|/php:result)">Hae uudestaan</xsl:when>
      <xsl:otherwise>Hae</xsl:otherwise>
    </xsl:choose>
  </xsl:variable>

  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi">
    <head>
      <title>Siirrettyjen numeroiden haku</title>
      <link rel="stylesheet" href="perustyyli.css" type="text/css" />
    </head>
    <body>
      <div id="otsa" style="background-image: url(numero.png);">
	<h1>Siirrettyjen numeroiden haku</h1>
      </div>

      <div id="runko">
	<xsl:apply-templates />

	<h2><xsl:value-of select="$otsikko"/></h2>
	  <form method="get" action=".">
	
	<p>
	    <label for="telephone">Puhelinnumero:</label>
	    <input id="telephone" name="telephone" size="15" type="text"
		   value="{(/php:error|/php:result)/@number_raw}"/>
	    <input type="submit" value="Tarkista"
		   title="Tarkista operaattori."/>

	</p>
	  </form>	
	<h2>Tietoja palvelusta</h2>
	
	<p>Tiedot noudetaan Numpacin ylläpitämästä siirrettyjen
	numeroiden tietokannoista. <a href="miksi.html">Lisätietoja</a>
	käyttöehdoista ja siitä, miksi tämä sivu on ylipäätään
	olemassa.</p>

	<p>
	  Palvelun on kirjoittanut PHP:llä ja XSLT:llä <a href="../.."
	  title="Joel Lehtosen kotisivut"> Joel Lehtonen</a>. Sivun
	  lähdekoodit ovat <a href="../../repo/siirretyt.git"
	  title="Linkki lähdekoodisivulle">saatavilla</a>.
	</p>

      </div>
    </body>
    </html>
  </xsl:template>

  <xsl:template match="/php:error">
    <h2 class="error">Virhe!</h2>
    
    <p><xsl:value-of select="."/></p>

    <p>Mikäli ongelmat jatkuvat, saattaa syynä olla, että Numpac on
    muuttanut sivujensa rakennetta. Voit yrittää hakea numeroa suoraan
    alkuperäisiltä sivulta osoitteesta http://siirretytnumerot.fi/
    .</p>
  </xsl:template>

  <xsl:template match="/php:result">
    <h2>Onnistui!</h2>
    
    <img src="{@img}" alt="Operaattorin nimi"/>
    <p>Numeron operaattoritunnus on <xsl:value-of select="@prefix"/>.</p>
  </xsl:template>



</xsl:stylesheet>
