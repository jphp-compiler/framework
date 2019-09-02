<?php

use packager\Event;

/**
 * @jppm-task hub:publish
 */
function task_hubPublish(Event $event)
{
    foreach ($event->package()->getAny('modules', []) as $i => $module) {
        Tasks::copy("./package.hub.yml", "./$module", true);
        Tasks::runExternal("./$module", 'hub:publish', [], ...$event->flags());

        if ($i == 0) {
            Tasks::copy("./wizard-core/package.hub.yml", "./");
            Tasks::deleteFile("./$module/package.hub.yml");
        }
    }
}

/**
 * @jppm-task doc:build
 */
function task_docBuild(Event $event)
{
    foreach ($event->package()->getAny('modules', []) as $i => $module) {
        Tasks::runExternal("./$module", 'doc:build');
    }
}

/**
 * @jppm-task build
 */
function task_publish(Event $event)
{
    foreach ($event->package()->getAny('modules', []) as $i => $module) {
        Tasks::runExternal("./$module", 'install', [], ...$event->flags());
        Tasks::runExternal("./$module", 'wizard:build', [], ...$event->flags());
    }
}

function task_test(Event $event)
{
    Tasks::runExternal("./wizard-core", "install");
    Tasks::runExternal("./wizard-core", "test");
}

