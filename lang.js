const testFolder = './languages/';
const fs = require('fs');
const { exec } = require("child_process");

fs.readdir(testFolder, (err, files) => {
  files.forEach(file => {
	if(file.slice(-2) === "po") {
		let basename = file.slice(0,-2);
		exec("msgfmt ./languages/" + file + " -o ./languages/" + basename + "mo", (error, stdout, stderr) => {
			console.log("compiled " + file)
			console.log("error", error)
			console.log("stderr", stderr)
			console.log("stdout", stdout)
		})
	}
  });
});