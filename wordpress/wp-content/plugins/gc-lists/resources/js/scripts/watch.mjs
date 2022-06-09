import shell from 'shelljs';
import notifier from 'node-notifier';

notifier.notify({title: 'GC Articles', message: 'Change detected'});
shell.exec(`npm run build`)
notifier.notify({ title: 'GC Articles', message: 'Build completed' });
