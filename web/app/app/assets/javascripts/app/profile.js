(function(){

    $('#profile_contacts').duplicator({
      row: ".row",
      remotes: {
        post: '/profile_contacts',
        put: '/profile_contacts',
        delete: '/profile_contacts',
        get: '/profile_contacts'
      }
    });
    $('#profile_educations').duplicator({
      row: ".row",
      remotes: {
        post: '/profile_educations',
        put: '/profile_educations',
        delete: '/profile_educations',
        get: '/profile_educations'
      }
    });
    $('#profile_emergency').duplicator({
      row: ".row",
      remotes: {
        post: '/profile_emergencies',
        put: '/profile_emergencies',
        delete: '/profile_emergencies',
        get: '/profile_emergencies'
      }
    });
    $('#profile_employment').duplicator({
      row: ".row",
      remotes: {
        post: '/profile_employment_history',
        put: '/profile_employment_history',
        delete: '/profile_employment_history',
        get: '/profile_employment_history'
      }
    });
    $('#profile_family').duplicator({
      row: ".row",
      remotes: {
        post: '/profile_family',
        put: '/profile_family',
        delete: '/profile_family',
        get: '/profile_family'
      }
    });
  
}).call(this);