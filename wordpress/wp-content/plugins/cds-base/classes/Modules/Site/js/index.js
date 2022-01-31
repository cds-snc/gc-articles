const API = {};

API.getContent = async ($, BASE_SITE, slug) => {
   return new Promise(async (resolve, reject) => {
      const page = await $.ajax({
         type: "GET",
         headers: {
            'X-WP-Nonce': CDS_VARS.rest_nonce
         },
         url: BASE_SITE + `/wp-json/wp/v2/pages?slug=${slug}`,
      });

      if (page && page.length >= 1) {
         resolve({ title: page[0].title.rendered, content: page[0].content.rendered.replace(/(\r\n|\n|\r)/gm, "") });
      }

      reject({ message: `failed to retrieve content ${slug}` });
   });
}

API.createPage = ($, data) => {
   const endpoint = window.ADMIN_REST.ENDPOINT;

   return new Promise(async (resolve, reject) => {
      const result = await $.ajax({
         type: "POST",
         headers: {
            'X-WP-Nonce': CDS_VARS.rest_nonce
         },
         url: endpoint + "/wp-json/wp/v2/pages",
         data: data
      });

      if (result && result.id) {
         resolve(result.id)
      }

      reject({ message: `failed to create ${data.title}` })
   });
}

API.translatePage = async ($, data) => {

   const endpoint = window.ADMIN_REST.ENDPOINT;

   return new Promise(async (resolve, reject) => {
      const result = await $.ajax({
         type: "POST",
         headers: {
            'X-WP-Nonce': CDS_VARS.rest_nonce
         },
         url: endpoint + "/wp-json/cds-wpml/v1/translate",
         data: data
      });

      if (result && result.post_translated_id) {
         resolve(result.post_translated_id)
      }

      reject({ message: `failed to create translate ${data.post_id}` })
   });
}

API.setOptions = async ($, data) => {
   const endpoint = window.ADMIN_REST.ENDPOINT;

   return new Promise(async (resolve, reject) => {
      const result = await $.ajax({
         type: "POST",
         headers: {
            'X-WP-Nonce': CDS_VARS.rest_nonce
         },
         url: endpoint + `/wp-json/setup/v1/set-options`,
         data
      });

      resolve(result);
   });
}

(function ($) {
   
   const BASE_SITE = "/demo" // this is the site to pull content from

   $('.text-status').hide().removeClass("hidden");
   $('.loader-container').hide().removeClass("hidden");

   const createPage = async ($, BASE_SITE, slug) => {
      $('.text-status').text(`Retrieving ${slug}  page content`);
      const page = await API.getContent($, BASE_SITE, slug);

      if (page) {
         $('.text-status').text("Creating page");
         const result = await API.createPage($, { ...page, status: "publish" });
         return result;
      }
   }

   const initSetup = async () => {
      try {
         const homeId = await createPage($, BASE_SITE, "home");
         const maintenanceId = await createPage($, BASE_SITE, "maintenance");
         await API.setOptions($, { homeId, maintenanceId });

         $('.text-status').delay(500).text("Creating page translations");
         await API.translatePage($, { post_id: homeId });
         await API.translatePage($, { post_id: maintenanceId });

         $('.loader-container').fadeOut();
         $('.text-status').html(`Finished. You can now <a href="users.php?page=users-add">add a user</a>.`);

      } catch (e) {
         $('.text-status').text(`⚠️ Error: ${e.message}`);
         $('.loader-container').fadeOut();
      }
   }

   $(document).on("click", "#add-pages", async (e) => {
      e.preventDefault();
      const target = e.target;
      $(target).addClass("disabled");
      $(document).off("click", "#add-pages");

      $(target).fadeOut(200, () => {
         $(".actions").remove();
         $('.text-status').delay(500).fadeIn(1000);
         $('.loader-container').delay(2000).fadeIn(1000, () => {
            initSetup();
         });
      });
   });
})(jQuery);