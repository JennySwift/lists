var UserRepository = {

    state: {
        me: me
    },

    /**
    *
    * @param user
    */
    updateUser: function (user) {
        this.state.me = user;
    }
};