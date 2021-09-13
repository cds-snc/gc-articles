import shell from 'shelljs';

export const createTaggedRelease = (version, notes = "bug fixes") => {
    if (shell.exec(`gh release create ${version} --notes "${notes}"`).code !== 0) {
        shell.echo('Error: gh release failed');
        shell.exit(1);
    }
}

