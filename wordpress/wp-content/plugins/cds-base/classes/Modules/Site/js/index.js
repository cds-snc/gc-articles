(function ($) {
   $('.text-status').hide().removeClass("hidden");
   $('.loader-container').hide().removeClass("hidden");

   const setOptions = async (data) => {
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

   // util function to pull page content
   const getPage = async (endpoint, slug) => {
      return new Promise(async (resolve, reject) => {
         const page = await $.ajax({
            type: "GET",
            headers: {
               'X-WP-Nonce': CDS_VARS.rest_nonce
            },
            url: endpoint + `/wp-json/wp/v2/pages?slug=${slug}`,
         });

         if (page && page.length >= 1) {
            resolve({ title: page[0].title.rendered, content: page[0].content.rendered.replace(/(\r\n|\n|\r)/gm, "") });
         }

         reject({ message: `failed to retrieve content ${slug}` });
      });
   }

   const createPage = (obj) => {
      const endpoint = window.ADMIN_REST.ENDPOINT;

      return new Promise(async (resolve, reject) => {
         const result = await $.ajax({
            type: "POST",
            headers: {
               'X-WP-Nonce': CDS_VARS.rest_nonce
            },
            url: endpoint + "/wp-json/wp/v2/pages",
            data: obj
         });

         if (result && result.id) {
            resolve(result.id)
         }

         reject({ message: `failed to create ${obj.title}` })
      });
   }

   const homePageSetup = async () => {
      $('.text-status').text("Retrieving home page content");
      const page = await getPage("/demo/", "home");

      if (page) {
         $('.text-status').text("Creating page");
         const result = await createPage({ ...page, status: "publish" });
         return result;
      }
   }

   const maintenancePageSetup = async () => {
      $('.text-status').text("Retrieving maintenance page content");
      const page = await getPage("/demo/", "maintenance");

      if (page) {
         $('.text-status').text("Creating page");
         const result = await createPage({ ...page, status: "publish" });
         return result;
      }
   }

   const initSetup = async () => {
      try {
         const homeId = await homePageSetup();
         const maintenanceId = await maintenancePageSetup();

         $('.text-status').delay(500).text("Setting site options");
         await setOptions({ homeId, maintenanceId });
         $('.text-status').text(`Finished`);
         $('.loader-container').fadeOut();

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

   //https://articles.cdssandbox.xyz/notification-gc-notify/wp-json/wp/v2/pages 
})(jQuery);