<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="text" encoding="utf-8"/>

    <xsl:param name="name"/>
    <xsl:param name="driver"/>
    <xsl:param name="host"/>
    <xsl:param name="port"/>
    <xsl:param name="database"/>
    <xsl:param name="user"/>
    <xsl:param name="password"/>
    <xsl:param name="charset"/>

<xsl:template match="/">&lt;?php

use \siestaphp\driver\ConnectionFactory;
use \siestaphp\driver\ConnectionData;

// connection Details
$name = "<xsl:value-of select="$name"/>";
$driver = "<xsl:value-of select="$driver"/>";
$host = "<xsl:value-of select="$host"/>";
$port = <xsl:value-of select="$port"/>;
$database = "<xsl:value-of select="$database"/>";
$user = "<xsl:value-of select="$user"/>";
$password = "<xsl:value-of select="$password"/>";
$charset = "<xsl:value-of select="$charset"/>";
$postConnectStatementList = array("SET NAMES UTF8");

$cd = new ConnectionData($name, $driver, $host, $port, $database, $user, $password, $charset, $postConnectStatementList);
ConnectionFactory::addConnection($cd);
</xsl:template>
</xsl:stylesheet>