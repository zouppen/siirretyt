<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns="http://www.w3.org/1999/xhtml"
		xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
		version="1.0">

  <xsl:output method="text" encoding="UTF-8" media-type="text/plain" />

  <!-- end of the boilerplate -->
  
  <xsl:template match="/">
    <xsl:value-of select="//p[@class='bodytext']/img/@src"/>
    <xsl:text>&#10;</xsl:text>
  </xsl:template>
  
</xsl:stylesheet>
