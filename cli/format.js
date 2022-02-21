import watch from "watch";
import shell from 'shelljs';

watch.createMonitor('./wordpress', function (monitor) {
  monitor.files['*.php'];

  console.log("Watching...");

  monitor.on("changed", function (f, curr, prev) {
    console.clear();
    console.log("File changed: " + f);
    shell.exec(`./wordpress/vendor/bin/phpcbf -n --standard=PSR12 ${f}`)
  })

  // monitor.stop(); // Stop watching
})
