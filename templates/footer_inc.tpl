{if $gBitSystem->getConfig('badbehavior_footer', 'n') == 'y'}<p style="text-align:center"><a href="http://www.bad-behavior.ioerror.us/">{tr}Bad Behavior{/tr}</a> {tr}has blocked{/tr} <strong>{php}echo bb2_insert_stats();{/php}</strong> {tr}access attempts in the last 7 days{/tr}</p>{/if}
