#!/usr/bin/env node
import replace from 'replace-in-file';

const replaceContent = async (options) => {
  const results = await replace(options);
  console.log('Replacement results:', results);
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

  await replaceContent({
    files: 'wordpress/wp-content/themes/cds-default/style.css',
    from: new RegExp("Version:.*", 'i'),
    to: `Version: ${version}`,
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

const updatePackageJsonVersion = async (version) => {

  await replaceContent({
    files: 'wordpress/wp-content/mu-plugins/cds-base/package.json',
    from: new RegExp('"version":.*"', 'i'),
    to: `"version": "${version}"`,
  });

  await replaceContent({
    files: 'package.json',
    from: new RegExp('"version":.*"', 'i'),
    to: `"version": "${version}"`,
  });

}

export const updateVersion = async (version) => {
  await updatePackageJsonVersion(version);
  await writeVersionFile(version);
  await updateWordPressThemeVersion(version);
  await updateWordPressPluginVersion(version);
}

export const updateTerragruntHcl = async (tag) => {
  // update wordpress_image_tag
  await replaceContent({
    files: 'infrastructure/terragrunt/env/staging/ecs/terragrunt.hcl',
    from: new RegExp('wordpress_image_tag.*', 'i'),
    to: `wordpress_image_tag      = "${tag}"`,
  });
}
