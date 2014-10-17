(function(){
    $('#profile_contacts').duplicator({
      row: ".row",
      remotes: {
        post: '/useradminprofile/profile-contacts/' + user_profile_id ,
        put: '/useradminprofile/profile-contacts/' + user_profile_id ,
        delete: '/useradminprofile/profile-contacts/' + user_profile_id ,
        get: '/useradminprofile/profile-contacts/' + user_profile_id
      }
    });
    $('#profile_educations').duplicator({
      row: ".row",
      remotes: {
        post: '/useradminprofile/profile-educations/' + user_profile_id ,
        put: '/useradminprofile/profile-educations/' + user_profile_id ,
        delete: '/useradminprofile/profile-educations/' + user_profile_id ,
        get: '/useradminprofile/profile-educations/' + user_profile_id
      }
    });
    $('#profile_emergency').duplicator({
      row: ".row",
      remotes: {
        post: '/useradminprofile/profile-emergencies/' + user_profile_id ,
        put: '/useradminprofile/profile-emergencies/' + user_profile_id ,
        delete: '/useradminprofile/profile-emergencies/' + user_profile_id ,
        get: '/useradminprofile/profile-emergencies/' + user_profile_id
      }
    });
    $('#profile_employment').duplicator({
      row: ".row",
      remotes: {
        post: '/useradminprofile/profile-employment-history/' + user_profile_id ,
        put: '/useradminprofile/profile-employment-history/' + user_profile_id ,
        delete: '/useradminprofile/profile-employment-history/' + user_profile_id ,
        get: '/useradminprofile/profile-employment-history/' + user_profile_id
      }
    });
    $('#profile_family').duplicator({
      row: ".row",
      remotes: {
        post: '/useradminprofile/profile-family/' + user_profile_id ,
        put: '/useradminprofile/profile-family/' + user_profile_id ,
        delete: '/useradminprofile/profile-family/' + user_profile_id ,
        get: '/useradminprofile/profile-family/' + user_profile_id
      }
  });
  
}).call(this);