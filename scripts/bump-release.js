#!/usr/bin/env node
import replace from 'replace-in-file';
import yargs from "yargs";

const argv = yargs(process.argv.slice(2)).argv;

const replaceContent = async (options) => {
    const results = await replace(options);
    console.log('Replacement results:', results);
}

const updateTerragruntHcl = async (commit) => {
    // update wordpress_image_tag
    await replaceContent({
        files: 'infrastructure/terragrunt/env/prod/ecs/terragrunt.hcl',
        from: new RegExp('wordpress_image_tag.*', 'i'),
        to: `wordpress_image_tag      = "${commit}"`,
    });
}

const writeVersionFile = async (version) => {
    await replaceContent({
        files: 'VERSION',
        from: new RegExp(/.*/g, 'i'),
        to: version,
    });
}

const updateWordPressThemeVersion = async (version) => {
    await replaceContent({
        files: 'wordpress/wp-content/themes/cds-default/functions.php',
        from: new RegExp("_S_VERSION',.*", 'i'),
        to: `_S_VERSION', '${version}');`,
    });
}

const updateWordPressPluginVersion = async (version) => {
    await replaceContent({
        files: 'wordpress/wp-content/mu-plugins/cds-base/index.php',
        from: new RegExp("BASE_PLUGIN_NAME_VERSION',.*", 'i'),
        to: `BASE_PLUGIN_NAME_VERSION', '${version}');`,
    });

    await replaceContent({
        files: 'wordpress/wp-content/mu-plugins/cds-base/index.php',
        from: new RegExp("Version:.*", 'i'),
        to: `Version: ${version}`,
    });
}

try {

    if (!argv.version_tag && !argv.version_num) {
        throw new Error("missing --version_tag or --version_num argument")
    }

    if (argv.version_num) {
        // pre-release commit
        const version = argv.version_num;
        await writeVersionFile(version);
        await updateWordPressThemeVersion(version);
        await updateWordPressPluginVersion(version);
    }

    if (argv.version_tag) {
        const version_tag = argv.version_tag;
        // pass the github release tag
        await updateTerragruntHcl(version_tag);
    }
}
catch (error) {
    console.error(`\nError: ${error.message}`);
}