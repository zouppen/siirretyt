<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns="http://www.w3.org/1999/xhtml"
		xmlns:php="http://iki.fi/zouppen/2010/php"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		version="1.0">
		
  <xsl:output doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" 
	      method="xml" omit-xml-declaration="no" encoding="UTF-8" 
	      indent="yes"
	      doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
	      media-type="text/html"
	      />

  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fi">
    <head>
      <title>Siirrettyjen numeroiden haku</title>
      <link rel="stylesheet" href="../perustyyli.css" type="text/css" />
    </head>
    <body>
      <div id="otsa" style="background-image: url(numero.png);">
	<h1>Siirrettyjen numeroiden haku</h1>
      </div>

      <div id="runko">
	<xsl:apply-templates />
      </div>
    </body>
    </html>
  </xsl:template>

  <xsl:template match="/php:error">
    <h2>Virhe!</h2>
    
    <p><xsl:value-of select="."/></p>
  </xsl:template>

</xsl:stylesheet>
