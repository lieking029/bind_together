<?php

namespace App\DataTables;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{

    protected $filterByRole;

    public function __construct()
    {
        $this->filterByRole = Session::get('filterByStatus', request()->get('status'));
    }

    public function with(array|string $key, mixed $value = null): static
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->{$k} = $v;
                Session::put($k, $v);
            }
        } else {
            $this->{$key} = $value;
            Session::put($key, $value);
        }


        return $this;
    }

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('actions', fn(User $user) => view('users.index', compact('user')))
            ->rawColumns(['actions']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['roles'])
            ->when($this->filterByRole, function ($query, $status) {
                $role = match ($status) {
                    'super_admin' => UserTypeEnum::SUPERADMIN,
                    'admin_sport' => UserTypeEnum::ADMINSPORT,
                    'admin_org' => UserTypeEnum::ADMINORG,
                    'coach' => UserTypeEnum::COACH,
                    'adviser' => UserTypeEnum::ADVISER,
                    'student' => UserTypeEnum::STUDENT,
                };

                if ($role) {
                    $query->whereIn('status', (array) $role);
                }
            });
            return $query->select('users.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('user_table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('Fullname'),
            Column::make('gender'),
            Column::make('email'),
            Column::make('role'),
            Column::computed('actions')
                  ->exportable(false)
                  ->printable(false),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'User_' . date('YmdHis');
    }
}
