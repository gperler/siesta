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

<xsl:template match="/">
{
    "version" : "1.0",
    "connection": [ {
        "name": "<xsl:value-of select="$name"/>",
        "driver" : "<xsl:value-of select="$driver"/>",
        "isDefault": true,
        "host": "<xsl:value-of select="$host"/>",
        "port": <xsl:value-of select="$port"/>,
        "database": "<xsl:value-of select="$database"/>",
        "user": "<xsl:value-of select="$user"/>",
        "password": "<xsl:value-of select="$password"/>",
        "charset": "<xsl:value-of select="$charset"/>",
        "postConnectStatementList": [
            "SET NAMES UTF8"
        ]
    }],
    "migration": {
        "method": "direct",
        "targetPath": "",
        "dropUnusedTables": false
    },
    "reverse": {
        "targetPath": "",
        "targetNamespace": ""
    }
}
</xsl:template>
</xsl:stylesheet>