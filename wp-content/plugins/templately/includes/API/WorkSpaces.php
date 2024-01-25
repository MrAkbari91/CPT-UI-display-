<?php

namespace Templately\API;

use WP_Error;
use WP_REST_Request;

class WorkSpaces extends API {
    private $endpoint = 'workspaces';

    public function register_routes() {
        $this->get( $this->endpoint, [ $this, 'get_workspaces' ] );
        $this->get( $this->endpoint . '/(?P<slug>[a-zA-Z0-9-]+)', [ $this, 'get_workspaces_details' ] );
        $this->get( $this->endpoint . '/item/(?P<id>[a-zA-Z0-9-]+)', [ $this, 'get_item' ] );
        $this->get( $this->endpoint . '/item/delete/(?P<id>[a-zA-Z0-9-]+)', [ $this, 'delete_item' ] );

        $this->post( $this->endpoint . '/create', [ $this, 'create_workspace' ] );
        $this->post( $this->endpoint . '/(?P<slug>[a-zA-Z0-9-]+)', [ $this, 'edit_workspace' ] );
        $this->post( $this->endpoint . '/delete/(?P<id>[a-zA-Z0-9-]+)', [ $this, 'delete_workspaces' ] );
        $this->post( $this->endpoint . '/item/add', [ $this, 'add_item' ] );
    }

    /**
     * @param $message mixed actual error message.
     * @param $endpoint string endpoint, which for the error is triggering.
     *
     * @return WP_Error
     */
    protected function permission_error( $message, $endpoint = '' ) {
        $_message = '';
        if ( $endpoint === $this->get_namespace( 'workspaces' ) ) {
            $_message = __( 'Sorry, you need to login/re-login to get your workspace list.', 'templately' );
        }

        return parent::permission_error( $_message, $endpoint );
    }

    /**
     * Get WorkSpace List.
     * This will give paged lists for WorkSpaces.
     *
     * @return mixed
     */
    public function get_workspaces() {
        $shared    = $this->get_param( 'shared', false, 'rest_sanitize_boolean' );
        $per_page  = $this->get_param( 'per_page', 15, 'intval' );
        $page      = $this->get_param( 'page', 1, 'intval' );
        $platform  = $this->get_param( 'platform', 'elementor' );
        $list_only = $this->get_param( 'list_only', false, 'rest_sanitize_boolean' );

        $query = 'data{ id, name, slug, sharedWith{ id, name, profile_photo }, owner { id, name, profile_photo }, pending_invitations }, total_page, current_page';

        if ( $list_only ) {
            $per_page = -1;
            $query    = 'data{ id, name, slug }';
        }

        return $this->http()->query(
            $shared ? 'sharedWorkspaces' : 'workspaces',
            $query,
            [
                'api_key'   => $this->api_key,
                'page'      => $page,
                'file_type' => $platform,
                'per_page'  => $per_page
            ]
        )->post();
    }

    public function get_workspaces_details() {
        $_slug = $this->get_param( 'slug' );

        if ( empty( $_slug ) ) {
            return $this->error(
                'invalid_id',
                __( 'Invalid workspace slug for get workspace.', 'templately' ), 'workspaces/:id', 400
            );
        }

        $directLoad     = $this->get_param( 'directLoad', false, 'boolval' );
        $files_per_page = $this->get_param( 'per_page', 10, 'intval' );
        $files_page     = $this->get_param( 'page', 1, 'intval' );
        $file_type      = $this->get_param( 'file_type', 'elementor' );
        $search_keyword = $this->get_param( 'search' );

        $funcArgs = [
            'api_key'        => $this->api_key,
            'slug'           => $_slug,
            'files_page'     => $files_page,
            'files_per_page' => $files_per_page,
            'file_type'      => $file_type
        ];

        if ( ! empty( $search_keyword ) ) {
            $funcArgs['files_search'] = $search_keyword;
        }

        $query_params = 'files{ total_page, current_page, data{ id, my_cloud_id, name, owner{ id }, last_modified } }';

        if ( $directLoad ) {
            $query_params = 'id, name, slug, sharedWith{ id, name, email, profile_photo }, owner{ id, name, profile_photo }, pending_invitations,' . $query_params;
        }

        $response = $this->http()->query(
            'workspaceDetails',
            $query_params,
            $funcArgs
        )->post();

        if ( is_null( $response ) ) {
            return $this->error(
                'workspace_not_exists',
				__( 'The workspace you looking for is not exists anymore.', 'templately' ),
				'workspaces/:slug',
				404
			);
        }

        return $response;
    }

    /**
     * Creating a workspace in clouds.
     *
     * @return mixed
     */
    public function create_workspace() {
        $name = $this->get_param( 'title' );

        if ( empty( $name ) ) {
            return $this->error(
                'invalid_name',
                __( 'Workspace name cannot be empty.', 'templately' ),
                'workspaces/create',
                400
            );
        }

        $shared_with = $this->get_param( 'share_with' );

        $funcArgs = [
            'api_key' => $this->api_key,
            'name'    => $name
        ];

        if ( ! empty( $shared_with ) ) {
            $funcArgs['share_with'] = wp_slash( json_encode( $shared_with ) );
        }

        return $this->http()->mutation(
            'createWorkspace',
            'status, message, workspace{ id, name, slug, sharedWith{ id, name, profile_photo }, owner{ id, name, profile_photo }, pending_invitations }',
            $funcArgs
        )->post();
    }

    /**
     * Editing WorkSpace.
     *
     * @return mixed
     */
    public function edit_workspace() {
        $isCreate = $this->get_param( 'isCreate', false, 'rest_sanitize_boolean' );

        if ( $isCreate ) {
            /**
             * Passes the call to self::create_workspace if the request is for create.
             */
            return $this->create_workspace();
        }

        $id         = $this->get_param( 'id', false, 'intval' );
        $title      = $this->get_param( 'title' );
        $platform   = $this->get_param( 'platform', 'elementor' );
        $share_with = $this->get_param( 'share_with' );
        $remove     = $this->get_param( 'remove' );

        if ( empty( $title ) ) {
            return $this->error(
                'invalid_name',
                __( 'Workspace name cannot be empty.', 'templately' ),
                'workspaces/create',
                400
            );
        }

        $post_args = [
            'api_key'   => $this->api_key,
            'id'        => $id,
            'name'      => $title,
            'file_type' => $platform
        ];

        if ( ! empty( $share_with ) ) {
            $post_args['share_with'] = wp_slash( json_encode( $share_with ) );
        }

        if ( ! empty( $remove ) ) {
            $post_args['remove'] = wp_slash( json_encode( $remove ) );
        }

        return $this->http()->mutation(
            'editWorkspace',
            'status, message, workspace{ id, name, slug, sharedWith{ id, name, profile_photo }, owner{ id, name, profile_photo }, pending_invitations }',
            $post_args
        )->post();
    }

    /**
     * Deletes an workspace from the cloud.
     *
     * @return void|WP_Error|array
     */
    public function delete_workspaces() {
        $_id          = $this->get_param( 'id', false, 'intval' );
        $delete_files = $this->get_param( 'delete_files', false, 'boolval' );

        if ( ! $_id ) {
            return $this->error(
                'invalid_workspace_id',
                __( 'You should provide a valid WorkSpace ID.', 'templately' ),
                'workspaces/:slug', 404
            );
        }

        $funcArgs = [
            'api_key' => $this->api_key,
            'id'      => $_id
        ];

        if ( $delete_files ) {
            $funcArgs['delete_files'] = 'true';
        }

        return $this->http()->mutation(
            'deleteWorkspace',
            'status, message',
            $funcArgs
        )->post();
    }

    public function add_item() {
        $workspace_id = $this->get_param( 'workspace_id', 0, 'intval' );
        $files        = $this->get_param( 'files', false, 'intval' );

        if ( $workspace_id <= 0 ) {
            return $this->error(
                'invalid_workspace_id',
                __( 'Invalid Workspace ID.', 'templately' ), '/workspaces/item/add', 501 );
        }

        if ( ! $files ) {
            return $this->error( 'invalid_files_id', __( 'Invalid Files ID.', 'templately' ), '/workspaces/item/add', 501 );
        }

        $funcArgs = [
            'api_key'      => $this->api_key,
            'workspace_id' => $workspace_id,
            'files'        => json_encode( $files )
        ];

        return $this->http()->mutation(
            'addFileToWorkspace',
            'data, status',
            $funcArgs
        )->post();
    }

    public function get_item( WP_REST_Request $request ) {
        $_id = intval( $request->get_param( 'id' ) );

        if ( $_id <= 0 ) {
            return $this->error( 'invalid_id', __( 'Invalid item id for get cloud item.', 'templately' ), 'workspaces/item/:id', 400 );
        }

        return $this->http()->mutation(
            'downloadMyCloudItem',
            'file, status, message, file_name, file_type',
            [
                'api_key' => $this->api_key,
                'id'      => $_id
            ]
        )->post();
    }

	/**
	 * Delete an item from WorkSpace
	 *
	 * @since 2.0.0
	 *
	 * @param WP_REST_Request $request REST request arguments
	 *
	 * @return mixed
	 */
    public function delete_item( WP_REST_Request $request ) {
        $_id = intval( $request->get_param( 'id' ) );

        if ( $_id <= 0 ) {
            return $this->error(
				'invalid_id',
				__( 'Invalid item id for delete cloud item.', 'templately' ),
				'workspaces/item/delete/:id',
				400
			);
        }

        return $this->http()->mutation(
            'deleteWorkspaceFile',
            'status, data, message',
            [
                'api_key' => $this->api_key,
                'file_id' => $_id
            ]
        )->post();
    }
}