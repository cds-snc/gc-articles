import shell from 'shelljs';
import notifier from 'node-notifier';

import fs from 'fs';

let settings;

try {
    settings = JSON.parse(fs.readFileSync('.watch.json', { encoding: 'utf8', flag: 'r' }));
    console.log("Settings file found, notifications are " + (settings.notifications ? "on" : "off"));
} catch (e) {
    console.log("No settings file found, notifcations are on by default");
    settings = JSON.parse('{ "notifications": true }');
}

const notifications = settings.notifications;

const start = new Date().getTime();

if (notifications) {
    notifier.notify({ title: 'GC Articles', message: 'Change detected' })
}

shell.exec('npm run build', function(code, stdout, stderr) {
    const end = new Date().getTime();
    const time = Math.floor((end - start) / 1000);
    if (code === 0) {
        if (notifications) {
            notifier.notify({
                title: 'GC Articles',
                message: `Build completed in ${time} seconds`
            });
        }
    } else {
        if (notifications) {
            notifier.notify({
                title: 'GC Articles',
                message: `Failed to compile in ${time} seconds`
            });
        }
    }
})
