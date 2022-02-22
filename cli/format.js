import watch from "watch";
import shell from 'shelljs';
import notifier from 'node-notifier';

watch.createMonitor('./wordpress', function (monitor) {
  monitor.files['*.php'];

  console.log("Watching...");
  notifier.notify({ title: 'GC Articles', message: 'Watching...' });

  monitor.on("changed", function (f, curr, prev) {
    console.clear();
    console.log("File changed: " + f);
    const code = (shell.exec(`./wordpress/vendor/bin/phpcbf -n --standard=PSR12 ${f}`)).code;

    if( code === 1) {
      notifier.notify({ title: 'GC Articles', message: 'Formatting fixed: ' + f });
    }
  })
})
