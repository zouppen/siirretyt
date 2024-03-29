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
      <xsl:otherwise>Hae operaattoria</xsl:otherwise>
    </xsl:choose>
  </xsl:variable>

  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi">
    <head>
      <title>Siirrettyjen numeroiden haku</title>
      <link rel="stylesheet" href="css/basic.css" type="text/css" />
      <link rel="stylesheet" href="css/mobile.css" type="text/css"
	    media="handheld" />
      <link rel="search" type="application/opensearchdescription+xml" title="Siirretyt numerot" href="opensearch"/>
      <link rel="icon" type="image/vnd.microsoft.icon" href="opensearch.ico" />
    </head>
    <body>
      <div id="otsa">
	<h1>Siirrettyjen numeroiden haku</h1>
      </div>

      <div id="runko">
	<xsl:apply-templates />

	<h2><xsl:value-of select="$otsikko"/></h2>
	<form method="get" action="search">
	
	  <p>
	    <label for="telephone">Puhelinnumero: </label>
	    <input id="telephone" name="telephone" size="15" type="text"
		   value="{(/php:error|/php:result)/@number_raw}"/>
	    <input type="submit" value="Tarkista"
		   title="Tarkista operaattori."/>
	    <xsl:if test="(/php:error|/php:result)/@quick">
	      <input type="hidden" name="quick" value="1" />
	    </xsl:if>
	  </p>
	</form>

	<p>Voit antaa numeron joko suomalaisessa muodossa tai
	kansainvälisessä muodossa (etuliite +358 tai 00358). Numeroon
	mahdollisesti kuuluvat välimerkit voi jättää pois.
	</p>

	<xsl:if test="not((/php:error|/php:result)/@quick)">
	  <p class="fluff">Kannattaa lisätä tämä hakutoiminto
	    <strong>pikahakukoneiden</strong> listaan
	    avaamalla hakukoneluettelo selaimesi oikeasta yläkulmasta.
	  </p>
	</xsl:if>

	<p class="fluff">Tätä sivua voi myös vaivatta käyttää kännykällä.</p>

	<h2>Tietoja palvelusta</h2>
	
	<p>Tiedot noudetaan Numpacin ylläpitämästä siirrettyjen
	numeroiden tietokannasta. <a href="miksi" title="Lisätietoja
	käyttöehdoista ja taustoista">Lisätietoja</a> käyttöehdoista
	ja siitä, miksi tämä sivu on ylipäätään olemassa. Tarjolla on 
	myös ohjelmointihaluisille <a href="xml-rajapinta" 
	title="Ohjeet XML-rajapinnan käyttöön">XML-rajapinta</a> 
	hakuun. Myös
	<a href="rekisteriseloste" title="Rekisteriseloste">
	  rekisteriseloste</a> on
	saatavilla.</p>

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

    <xsl:if test="@numpac_error">
      <p>Mikäli ongelmat jatkuvat, saattaa syynä olla, että Numpac on
	muuttanut sivujensa rakennetta. Voit yrittää hakea numeroa suoraan
	alkuperäisiltä sivulta osoitteesta http://siirretytnumerot.fi/ .
      </p>
    </xsl:if>
  </xsl:template>

  <xsl:template match="/php:result[not(@op_name='')]" priority="2">
    <h2>Onnistui!</h2>

    <p>Numeron <strong>
	<xsl:value-of select="@prefix" /> <xsl:value-of select="@number" />
      </strong> operaattori on
      <a href="{@op_homepage}" title="Operaattorin kotisivulle">
	<xsl:value-of select="@op_name" />
      </a>
      (teleyritystunniste on <xsl:value-of select="@op_id" />).
    </p>
  </xsl:template>

  <xsl:template match="/php:result" priority="1">
    <h2>Onnistui!</h2>
    
    <img src="{@img}" alt="Operaattorin nimi"/>
  </xsl:template>

</xsl:stylesheet>
