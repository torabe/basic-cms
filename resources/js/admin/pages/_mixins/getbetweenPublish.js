const getBetweenPublish = {
    computed:{
        getBetweenPublish(){
            return (item) => {
                const publishAt = this.$dayjs(item.publish_at).format('YYYY-MM-DD HH:mm');
                return publishAt + ((item.unpublish_at) ?  ' ～ ' + this.$dayjs(item.unpublish_at).format('YYYY-MM-DD HH:mm') : '')
            };
        }
    }
};

export default getBetweenPublish;
