<?PHP
/* Copyright 2005-2016, Lime Technology
 * Copyright 2012-2016, Bergware International.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 */
?>
<?
// Wrapper functions
function parse_plugin_cfg($plugin, $sections=false) {
  $ram = "/usr/local/emhttp/plugins/$plugin/default.cfg";
  $rom = "/boot/config/plugins/$plugin/$plugin.cfg";
  $cfg = file_exists($ram) ? parse_ini_file($ram, $sections) : array();
  return file_exists($rom) ? array_replace_recursive($cfg, parse_ini_file($rom, $sections)) : $cfg;
}

function parse_cron_cfg($plugin, $job, $text = "") {
  $cron = "/boot/config/plugins/$plugin/$job.cron";
  if ($text) file_put_contents($cron, $text); else @unlink($cron);
  exec("/usr/local/sbin/update_cron");
}

function agent_fullname($agent, $state) {
  switch ($state) {
    case 'enabled' : return "/boot/config/plugins/dynamix/notifications/agents/$agent";
    case 'disabled': return "/boot/config/plugins/dynamix/notifications/agents-disabled/$agent";
    default        : return $agent;
  }
}

function plugin_update_available($plugin) {
  $script = '/usr/local/emhttp/plugins/dynamix.plugin.manager/scripts/plugin';
  $local = file_exists("/var/log/plugins/$plugin.plg") ? exec("$script version /var/log/plugins/$plugin.plg") : "";
  $remote = file_exists("/tmp/plugins/$plugin.plg") ? exec("$script version /tmp/plugins/$plugin.plg") : "";
  return strcmp($remote,$local)>0 ? $remote : "";
}
?>
