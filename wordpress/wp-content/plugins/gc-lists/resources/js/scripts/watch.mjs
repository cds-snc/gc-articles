import watch from "watch";
import shell from 'shelljs';
import notifier from 'node-notifier';


watch.createMonitor('./src', function (monitor) {
    monitor.files['*.ts, *.tsx'];

    console.log("Watching...");
    notifier.notify({ title: 'GC Articles', message: 'Watching...' });

    monitor.on("changed", function (f, curr, prev) {
        console.clear();
        console.log("File changed: " + f);

        const code = (shell.exec(`npm run build`)).code;

        notifier.notify({ title: 'GC Articles', message: 'Build completed' });
    })
})
